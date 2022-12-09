<?php
/**
 * Gateway
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\PayPal
 */

namespace Pronamic\WordPress\Pay\Gateways\PayPal;

use Pronamic\WordPress\Money\Money;
use Pronamic\WordPress\Money\TaxedMoney;
use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Pronamic\WordPress\Pay\Core\PaymentMethod;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Gateway
 *
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class Gateway extends Core_Gateway {
	/**
	 * Config.
	 *
	 * @var Config
	 */
	protected $config;

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
		parent::__construct();

		$this->config = $config;

		$this->set_method( self::METHOD_HTTP_REDIRECT );

		// Supported features.
		$this->supports = [
			'payment_status_request',
		];

		// Client.
		$this->client = new Client( $config );

		// Methods.
		$payment_method_paypal = new PaymentMethod( PaymentMethods::PAYPAL );
		$payment_method_paypal->set_status( 'active' );

		$this->register_payment_method( $payment_method_paypal );
	}

	/**
	 * Start.
	 *
	 * @param Payment $payment Payment.
	 * @return void
	 * @throws \Exception Throws exception on unsupported payment method.
	 * @see Plugin::start()
	 */
	public function start( Payment $payment ) {
		/**
		 * If the payment method of the payment is unknown (`null`), we will turn it into
		 * an PayPal payment.
		 */
		$payment_method = $payment->get_payment_method();

		if ( null === $payment_method ) {
			$payment->set_payment_method( PaymentMethods::PAYPAL );
		}

		/**
		 * This gateway can only process payments for the payment method PayPal.
		 */
		$payment_method = $payment->get_payment_method();

		if ( PaymentMethods::PAYPAL !== $payment_method ) {
			throw new \Exception(
				\sprintf(
					'The PayPal cannot process `%s` payments, only PayPal payments.',
					$payment_method
				)
			);
		}

		/**
		 * HTML Variables for PayPal Payments Standard
		 *
		 * @link https://developer.paypal.com/docs/paypal-payments-standard/integration-guide/Appx-websitestandard-htmlvariables/
		 * @link https://github.com/easydigitaldownloads/easy-digital-downloads/blob/2.9.26/includes/gateways/paypal-standard.php
		 */
		$url = $this->config->get_webscr_url();

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
		 * Cancel return.
		 *
		 * A URL to which PayPal redirects the buyers' browsers if they cancel checkout
		 * before completing their payments. For example, specify a URL on your website
		 * that displays the Payment Canceled page.
		 */
		$variables->set_value(
			'cancel_return',
			\urlencode(
				\add_query_arg(
					'hash',
					\wp_hash( (string) $payment->get_id() ),
					\rest_url( Integration::REST_ROUTE_NAMESPACE . '/cancel-return/' . $payment->get_id() )
				)
			)
		);

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
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( \array_key_exists( 'txn_id', $_POST ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification
			$transaction_id = \sanitize_text_field( \wp_unslash( $_POST['txn_id'] ) );

			$payment->set_transaction_id( $transaction_id );
		}

		// Status.
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( \array_key_exists( 'payment_status', $_POST ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification
			$payment_status = \sanitize_text_field( \wp_unslash( $_POST['payment_status'] ) );

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
	 * @link https://developer.paypal.com/docs/paypal-payments-standard/integration-guide/Appx-websitestandard-htmlvariables/
	 * @param Payment $payment Payment.
	 * @return array<string, string>
	 */
	private function get_shopping_cart_variables( Payment $payment ) {
		$variables = [];

		$lines = $payment->get_lines();

		if ( null === $lines || 0 === \count( $lines ) ) {
			$x = 1;

			$variables[ 'item_name_' . $x ] = 'Payment ' . $payment->get_id();
			// The price or amount of the product, service, or contribution, not including shipping, handling, or tax.
			$variables[ 'amount_' . $x ] = $this->format_amount( $payment->get_total_amount() );

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

			// The price or amount of the product, service, or contribution, not including shipping, handling, or tax.
			$total_amount = $line->get_total_amount();

			$amount = $total_amount instanceof TaxedMoney
				? $total_amount->get_excluding_tax()
				: $total_amount;

			$variables[ 'amount_' . $x ] = $this->format_amount( $amount );

			$tax_amount = $line->get_tax_amount();

			if ( null !== $tax_amount ) {
				$variables[ 'tax_' . $x ] = $this->format_amount( $tax_amount );
			}

			$x++;
		}

		return $variables;
	}
}
