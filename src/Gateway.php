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

use Pronamic\WordPress\Money\Money;
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
	 * Config.
	 * 
	 * @var Config
	 */
	protected $paypal_config;

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

		$this->paypal_config = $config;

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
		$url = $this->paypal_config->get_webscr_url();

		$variables = new Variables();

		$variables->set_business( $this->paypal_config->get_email() );
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
		 * Shipping.
		 * 
		 * We set the `no_shipping` variable to `1` so there is no prompt for
		 * an address. For now we require that each extensions requests the
		 * shipping details.
		 * 
		 * @link https://github.com/pronamic/wp-pronamic-pay/issues/158
		 */
		$variables->set_value( 'no_shipping', '1' );

		/**
		 * Currency
		 */
		$currency_code = $payment->get_total_amount()->get_currency()->get_alphabetic_code();

		$variables->set_value( 'currency_code', $currency_code );

		/**
		 * Items.
		 */
		$shopping_cart_variables = $this->get_shopping_cart_variables( $payment );

		foreach ( $shopping_cart_variables as $key => $value ) {
			$variables->set_value( $key, $value );
		}

		/**
		 * Return.
		 */
		$variables->set_value( 'return', \urlencode( $payment->get_return_url() ) );

		/**
		 * Return method.
		 *
		 * @link https://developer.paypal.com/docs/paypal-payments-standard/integration-guide/Appx-websitestandard-htmlvariables/#paypal-checkout-page-variables
		 */
		$variables->set_value( 'rm', '2' );

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
		$variables->set_value( 'custom', (string) $payment->get_id() );

		/**
		 * Address.
		 */
		$address = $payment->get_billing_address();

		if ( null !== $address ) {
			$variables->set_optional_value( 'address1', $address->get_line_1() );
			$variables->set_optional_value( 'address2', $address->get_line_2() );
			$variables->set_optional_value( 'city', $address->get_city() );
			$variables->set_optional_value( 'country', $address->get_country_code() );
			$variables->set_optional_value( 'zip', $address->get_postal_code() );
		}

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
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$valid = $this->client->validate_notification( $_POST );

		if ( ! $valid ) {
			return;
		}

		// Transaction ID.
		if ( \filter_has_var( \INPUT_POST, 'txn_id' ) ) {
			$payment->set_transaction_id( \filter_input( \INPUT_POST, 'txn_id', \FILTER_SANITIZE_STRING ) );
		}

		// Status.
		if ( \filter_has_var( \INPUT_POST, 'payment_status' ) ) {
			$payment_status = \filter_input( \INPUT_POST, 'payment_status', \FILTER_SANITIZE_STRING );

			$status = Statuses::transform( $payment_status );

			if ( null !== $status ) {
				$payment->set_status( $status );
			}
		}
	}

	/**
	 * Format amount.
	 * 
	 * @param Money $amount Money.
	 * @return string
	 */
	private function format_amount( Money $amount ) {
		return $amount->number_format( null, '.', '' );
	}

	/**
	 * Get the PayPal shopping cart variables from a payment.
	 * 
	 * @param Payment $payment Payment.
	 * @return array<string, string>
	 */
	private function get_shopping_cart_variables( Payment $payment ) {
		$variables = array();

		$lines = $payment->get_lines();

		if ( null === $lines || 0 === \count( $lines ) ) {
			$x = 1;

			$variables[ 'item_name_' . $x ] = 'Payment ' . $payment->get_id();
			$variables[ 'amount_' . $x ]    = $this->format_amount( $payment->get_total_amount() );

			return $variables;
		}

		$x = 1;

		foreach ( $lines as $line ) {
			$name = \sprintf(
				/* translators: %s: item index */
				\__( 'Item %s', 'pronamic_ideal' ),
				$x
			);

			$line_name = $line->get_name();

			if ( null !== $line_name && '' !== $line_name ) {
				$name = $line_name;
			}

			$variables[ 'item_name_' . $x ] = $name;
			$variables[ 'amount_' . $x ]    = $this->format_amount( $line->get_total_amount() );

			$tax_amount = $line->get_tax_amount();

			if ( null !== $tax_amount ) {
				$variables[ 'tax_' . $x ] = $this->format_amount( $tax_amount );
			}

			$x++;
		}

		return $variables;
	}
}
