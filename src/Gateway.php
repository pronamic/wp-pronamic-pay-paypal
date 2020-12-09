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

		$x = 1;

		$variables->set_value( 'item_name_' . $x, 'Payment ' . $payment->get_id() );
		$variables->set_value( 'amount_' . $x, $payment->get_total_amount()->get_value() );

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
		
	}
}
