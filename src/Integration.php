<?php
/**
 * Integration
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\PayPal
 */

namespace Pronamic\WordPress\Pay\Gateways\PayPal;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Integration
 *
 * @author  Remco Tolsma
 * @version 1.1.2
 * @since   1.0.0
 */
class Integration extends AbstractGatewayIntegration {
	/**
	 * REST route namespace.
	 *
	 * @var string
	 */
	const REST_ROUTE_NAMESPACE = 'pronamic-pay/paypal/v1';

	/**
	 * Construct PayPal integration.
	 *
	 * @param array<string, array<string>> $args Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = \wp_parse_args(
			$args,
			array(
				'id'            => 'paypal',
				'name'          => 'PayPal',
				'provider'      => 'paypal',
				'url'           => \__( 'https://www.paypal.com/', 'pronamic_ideal' ),
				'product_url'   => \__( 'https://www.paypal.com/', 'pronamic_ideal' ),
				'dashboard_url' => 'https://www.paypal.com/mep/dashboard',
				'manual_url'    => \__(
					'https://www.pronamic.eu/manuals/using-paypal-pronamic-pay/',
					'pronamic_ideal'
				),
				'supports'      => array(),
			)
		);

		parent::__construct( $args );
	}

	/**
	 * Setup.
	 */
	public function setup() {
		\add_filter(
			'pronamic_gateway_configuration_display_value_' . $this->get_id(),
			array( $this, 'gateway_configuration_display_value' ),
			10,
			2
		);

		\add_filter( 'pronamic_payment_provider_url_paypal', array( $this, 'payment_provider_url' ), 10, 2 );

		// Notifications controller.
		$notifications_controller = new NotificationsController( $this );

		$notifications_controller->setup();
	}

	/**
	 * Gateway configuration display value.
	 *
	 * @param string $display_value Display value.
	 * @param int    $post_id       Gateway configuration post ID.
	 * @return string
	 */
	public function gateway_configuration_display_value( $display_value, $post_id ) {
		$config = $this->get_config( $post_id );

		return $config->get_email();
	}

	/**
	 * Payment provider URL.
	 *
	 * @param string|null $url     Payment provider URL.
	 * @param Payment     $payment Payment.
	 * @return string|null
	 */
	public function payment_provider_url( $url, Payment $payment ) {
		$transaction_id = $payment->get_transaction_id();

		if ( null === $transaction_id ) {
			return $url;
		}

		return \sprintf( 'https://www.paypal.com/activity/payment/%s', $transaction_id );
	}

	/**
	 * Get settings fields.
	 *
	 * @return array<int, array<string, callable|int|string|bool|array<int|string,int|string>>>
	 */
	public function get_settings_fields() {
		$fields = array();

		// Business Id.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => \FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_paypal_email',
			'title'    => \_x( 'Email', 'paypal', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => \__( 'Enter your PayPal account\'s email.', 'pronamic_ideal' ),
		);

		// Return fields.
		return $fields;
	}

	/**
	 * Get configuration by post ID.
	 *
	 * @param int $post_id Post ID.
	 * @return Config
	 */
	public function get_config( $post_id ) {
		$mode  = $this->get_meta( $post_id, 'mode' );
		$email = $this->get_meta( $post_id, 'paypal_email' );

		return new Config( $mode, $email );
	}

	/**
	 * Get gateway.
	 *
	 * @param int $post_id Post ID.
	 * @return Gateway
	 */
	public function get_gateway( $post_id ) {
		$config = $this->get_config( $post_id );

		return new Gateway( $config );
	}
}
