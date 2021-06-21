<?php
/**
 * Integration test
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\PayPal
 */

namespace Pronamic\WordPress\Pay\Gateways\PayPal;

/**
 * Integration test
 *
 * @author  Remco Tolsma
 * @version 1.0.0
 * @since   1.0.0
 */
class IntegrationTest extends \WP_UnitTestCase {
	/**
	 * Integration.
	 *
	 * @var Integration
	 */
	private $integration;

	/**
	 * Setup.
	 */
	public function setUp() {
		parent::setUp();

		$this->integration = new Integration();
	}

	/**
	 * Test settings fields.
	 */
	public function test_settings_fields() {
		$fields = $this->integration->get_settings_fields();

		$this->assertCount( 1, $fields );
	}

	/**
	 * Test config / gateway.
	 */
	public function test_config_post() {
		$post_id = $this->factory->post->create();

		\update_post_meta( $post_id, '_pronamic_gateway_mode', Gateway::MODE_TEST );
		\update_post_meta( $post_id, '_pronamic_gateway_paypal_email', 'info@pronamic.nl' );

		$config = $this->integration->get_config( $post_id );

		$this->assertInstanceOf( Config::class, $config );
		$this->assertEquals( 'info@pronamic.nl', $config->get_email() );
		$this->assertEquals(
			'{"mode":"test","email":"info@pronamic.nl"}',
			\wp_json_encode( $config )
		);

		$gateway = $this->integration->get_gateway( $post_id );

		$this->assertInstanceOf( Gateway::class, $gateway );
	}
}
