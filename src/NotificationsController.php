<?php
/**
 * Notifications controller
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\PayPal
 */

namespace Pronamic\WordPress\Pay\Gateways\PayPal;

use WP_REST_Request;

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
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}

	/**
	 * REST API init.
	 *
	 * @link https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
	 * @link https://developer.wordpress.org/reference/hooks/rest_api_init/
	 *
	 * @return void
	 */
	public function rest_api_init() {
		\register_rest_route(
			Integration::REST_ROUTE_NAMESPACE,
			'/ipn-listener/(?P<id>[\d]+)',
			array(
				'args'                => array(
					'id' => array(
						'description' => __( 'PayPal gateway configuration ID.', 'pronamic_ideal' ),
						'type'        => 'integer',
					),
				),
				'methods'             => array( 'GET', 'POST' ),
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
		return true;
	}
}