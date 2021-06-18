<?php
/**
 * Notifications controller
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\PayPal
 */

namespace Pronamic\WordPress\Pay\Gateways\PayPal;

use WP_REST_Request;
use Pronamic\WordPress\Http\Facades\Http;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;

/**
 * Notification controller
 *
 * @link https://developer.paypal.com/docs/api-basics/notifications/ipn/
 * @link https://github.com/paypal/ipn-code-samples/tree/master/php
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class NotificationsController {
	/**
	 * Setup.
	 *
	 * @return void
	 */
	public function setup() {
		\add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}

	/**
	 * REST API init.
	 *
	 * @link https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
	 * @link https://developer.wordpress.org/reference/hooks/rest_api_init/
	 * @return void
	 */
	public function rest_api_init() {
		\register_rest_route(
			Integration::REST_ROUTE_NAMESPACE,
			'/ipn-listener',
			array(
				/**
				 * IPN and PDT variables.
				 * 
				 * @link https://developer.paypal.com/docs/api-basics/notifications/ipn/IPNandPDTVariables/
				 */
				'args'                => array(
					'custom' => array(
						'description' => \__( 'Custom.', 'pronamic_ideal' ),
						'type'        => 'string',
					),
					'payment_status' => array(
						'description' => \__( 'The status of the payment.', 'pronamic_ideal' ),
						'type'        => 'string',
					),
					'txn_id'         => array(
						'description' => \__( 'The merchant\'s original transaction identification number for the payment from the buyer, against which the case was registered.', 'pronamic_ideal' ),
						'type'        => 'string',
					),
					'parent_txn_id'  => array(
						'description' => \__( 'In the case of a refund, reversal, or canceled reversal, this variable contains the `txn_id` of the original transaction.', 'pronamic_ideal' ),
						'type'        => 'string',

					),
					'mc_currency'       => array(
						'description' => \__( 'For payment IPN notifications, this is the currency of the payment.', 'pronamic_ideal' ),
						'type'        => 'string',
					),
					'mc_gross'       => array(
						'description' => \__( 'Full amount of the customer\'s payment, before transaction fee is subtracted. Equivalent to payment_gross for USD payments. If this amount is negative, it signifies a refund or reversal, and either of those payment statuses can be for the full or partial amount of the original transaction.', 'pronamic_ideal' ),
						'type'        => 'string',
					),
				),
				'methods'             => 'POST',
				'callback'            => array( $this, 'rest_api_paypal_ipn' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * REST API PayPal Instant Payment Notification handler.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return object
	 */
	public function rest_api_paypal_ipn( WP_REST_Request $request ) {
		$body = $request->get_body();

		$custom = $request->get_param( 'custom' );

		if ( empty( $custom ) ) {
			return new \WP_Error(
				'rest_paypal_empty_custom_variable',
				\__( 'Empty `custom` PayPal variable.', 'pronamic_ideal ' ),
				array(
					'status' => 200,
				)
			);
		}

		$payment = \get_pronamic_payment( $custom );

		if ( null === $payment ) {
			return new \WP_Error(
				'rest_paypal_empty_custom_variable',
				\sprintf(
					\__( 'No payment found by `custom` variable: %s.', 'pronamic_ideal ' ),
					$custom
				),
				array(
					'status' => 200,
				)
			);
		}

		/**
		 * Instant Payment Notification Post Back URL.
		 * 
		 * @link https://developer.paypal.com/docs/api-basics/notifications/ipn/ht-ipn/
		 * @link https://developer.paypal.com/docs/api-basics/notifications/ipn/IPNImplementation/#specs
		 */
		$ipn_pb_url = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

		/**
		 * Prefix the returned message with the `cmd=_notify-validate` variable,
		 * but do not change the message fields, the order of the fields, or
		 * the character encoding from the original message.
		 */
		$pb_body = 'cmd=_notify-validate&' . $body;

		/**
		 * Send response messages back to PayPal.
		 */
		$response = Http::post( $ipn_pb_url, array(
			'headers' => array(
				/**
				 * Please ensure you provide a User-Agent header value that 
				 * describes your IPN listener, such as,
				 * `PHP-IPN-VerificationScript`.
				 */
				'User-Agent' => 'Pronamic-Pay-IPN-VerificationScript',
			),
			'body'    => $pb_body,
		) );

		$result = $response->body();

		if ( 'INVALID' === $result ) {
			return new \WP_Error(
				'rest_paypal_ipn_invalid',
				\__( 'IPN request invalid.', 'pronamic_ideal ' ),
				array(
					'status' => 200,
				)
			);
		}

		if ( 'VERIFIED' !== $result ) {
			return new \WP_Error(
				'rest_paypal_ipn_not_verified',
				\__( 'IPN request not verified.', 'pronamic_ideal ' ),
				array(
					'status' => 200,
				)
			);
		}

		/**
		 * Payment.
		 */
		switch ( $request->get_param( 'payment_status' ) ) {
			case 'Canceled_Reversal':

				break;
			case 'Completed':
				$payment->set_transaction_id( $request->get_param( 'txn_id' ) );
				$payment->set_status( PaymentStatus::SUCCESS );

				break;
			case 'Created':

				break;
			case 'Denied':

				break;
			case 'Expired':
				$payment->set_status( PaymentStatus::EXPIRED );

				break;
			case 'Failed':
				$payment->set_status( PaymentStatus::FAILURE );

				break;
			case 'Pending':
				$payment->set_status( PaymentStatus::OPEN );

				break;
			case 'Refunded':
				$mc_gross    = $request->get_param( 'mc_gross' );
				$mc_currency = $request->get_param( 'mc_currency' );

				$gross = new Money( $mc_gross, $mc_currency );

				$refunded_amount = $gross->absolute();

				$payment->set_transaction_id( $request->get_param( 'parent_txn_id' ) );
				$payment->set_refunded_amount( $refunded_amount );

				break;
			case 'Reversed':
				$payment->set_status( PaymentStatus::RESERVED );

				break;
			case 'Processed':

				break;
			case 'Voided':

				break;
		}

		$note = \sprintf(
			'<p>%s</p><pre>%s</pre>',
			\__( 'Received PayPal IPN request:', 'pronamic_ideal' ),
			(string) \wp_json_encode( $request->get_params(), \JSON_PRETTY_PRINT )
		);

		$payment->add_note( $note );

		$payment->save();

		/**
		 * Result.
		 */
		$result = (object) array(
			'body'   => $request->get_body(),
			'custom' => $request->get_param( 'custom' ),
			'result' => $result,
		);

		return $result;
	}
}
