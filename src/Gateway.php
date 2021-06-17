<?php
/**
 * Gateway
 *
 * @author Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license GPL-3.0-or-later
 * @package Pronamic\WordPress\Pay\Gateways\PayPal
 */

namespace Pronamic\WordPress\Pay\Gateways\PayPal;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Gateway
 *
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class Gateway extends Core_Gateway {
	/**
	 * Client.
	 *
	 * @var Client
	 */
	private $client;

	/**
	 * Constructs and initializes an PayPal gateway.
	 *
	 * @param Config $config Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct( $config );

		$this->set_method( self::METHOD_HTTP_REDIRECT );

		// Supported features.
		$this->supports = array(
			'payment_status_request',
		);

		// Client.
		$this->client = new Client( $config );
	}

	/**
	 * Get supported payment methods
	 *
	 * @see Core_Gateway::get_supported_payment_methods()
	 * @return array<string>
	 */
	public function get_supported_payment_methods() {
		return array(
			PaymentMethods::PAYPAL,
		);
	}

	/**
	 * Is payment method required to start transaction?
	 *
	 * @see Core_Gateway::payment_method_is_required()
	 * @return true
	 */
	public function payment_method_is_required() {
		return true;
	}

	/**
	 * Start.
	 *
	 * @param Payment $payment Payment.
	 * @return void
	 * @throws \InvalidArgumentException Throws exception if payment ID or currency is empty.
	 * @see Plugin::start()
	 */
	public function start( Payment $payment ) {
		/**
		 * HTML Variables for PayPal Payments Standard
		 *
		 * @link https://developer.paypal.com/docs/paypal-payments-standard/integration-guide/Appx-websitestandard-htmlvariables/
		 * @link https://github.com/easydigitaldownloads/easy-digital-downloads/blob/2.9.26/includes/gateways/paypal-standard.php
		 */
		$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

		$variables = new Variables();

		$variables->set_business( $this->config->get_email() );
		$variables->set_cmd( '_cart' );
		$variables->set_upload( true );

		/**
		 * Customer.
		 */
		$customer = $payment->get_customer();

		if ( null !== $customer ) {
			$variables->set_optional_value( 'email', $customer->get_email() );

			$name = $customer->get_name();

			if ( null !== $name ) {
				$variables->set_optional_value( 'first_name', $name->get_first_name() );
				$variables->set_optional_value( 'last_name', $name->get_last_name() );
			}
		}

		/**
		 * Currency
		 */
		$currency_code = $payment->get_total_amount()->get_currency()->get_alphabetic_code();

		if ( null !== $currency_code ) { 
			$variables->set_value( 'currency_code', $currency_code );
		}

		/**
		 * Items.
		 */
		$x = 1;

		$variables->set_value( 'item_name_' . $x, 'Payment ' . $payment->get_id() );
		$variables->set_value( 'amount_' . $x, $payment->get_total_amount()->get_value() );

		/**
		 * Return.
		 */
		$variables->set_value( 'return', $payment->get_return_url() );

		/**
		 * Notify URL.
		 */
		$notify_url = \rest_url( Integration::REST_ROUTE_NAMESPACE . '/ipn-listener' );

		/**
		 * Filters the PayPal notify URL.
		 *
		 * If you want to debug the PayPal notify URL you can use this filter
		 * to override the report URL. You could for example use a service like
		 * https://webhook.site/ to inspect the notify requests from PayPal.
		 *
		 * @param string $notify_url PayPal notify URL.
		 */
		$notify_url = \apply_filters( 'pronamic_pay_paypal_notify_url', $notify_url );

		$variables->set_value( 'notify_url', $notify_url );

		/**
		 * Custom.
		 * 
		 * Pass-through variable for your own tracking purposes, which buyers do not see.
		 */
		$variables->set_value( 'custom', $payment->get_id() );

		/**
		 * URL.
		 */
		$url = \add_query_arg( $variables->get_array(), $url );

		$payment->set_action_url( $url );
	}

	/**
	 * Update status of the specified payment.
	 *
	 * @param Payment $payment Payment.
	 * @return void
	 */
	public function update_status( Payment $payment ) {
		// @todo handle status updates
	}
}
