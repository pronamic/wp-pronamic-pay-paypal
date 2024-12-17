<?php
/**
 * Pronamic Pay PayPal Add-On
 *
 * @package           Pronamic\WordPress\Pay\Gateways\PayPal
 * @author            Pronamic <info@pronamic.eu>
 * @copyright         2021 Pronamic
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Pronamic Pay PayPal Add-On
 * Plugin URI: https://www.pronamic.eu/plugins/pronamic-pay-paypal/
 * Description: Extend the Pronamic Pay plugin with the PayPal gateway to receive payments with PayPal through a variety of WordPress plugins.
 *
 * Version: 2.3.7
 * Requires at least: 4.7
 * Requires PHP: 7.4
 *
 * Author: Pronamic
 * Author URI: https://www.pronamic.eu/
 *
 * Text Domain: pronamic-pay-paypal
 * Domain Path: /languages/
 *
 * License: GPL-3.0-or-later
 *
 * Requires Plugins: pronamic-ideal
 * Depends: wp-pay/core
 *
 * GitHub URI: https://github.com/wp-pay-gateways/paypal
 *
 * Update URI: https://www.pronamic.eu/plugins/pronamic-pay-paypal/
 */

add_filter(
	'pronamic_pay_gateways',
	function( $gateways ) {
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\PayPal\Integration(
			[
				'id'         => 'paypal',
				'name'       => 'PayPal',
				'mode'       => 'live',
				'webscr_url' => 'https://www.paypal.com/cgi-bin/webscr',
				'ipn_pb_url' => 'https://ipnpb.paypal.com/cgi-bin/webscr',
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\PayPal\Integration(
			[
				'id'         => 'paypal-sandbox',
				'name'       => 'PayPal - Sandbox',
				'mode'       => 'test',
				'webscr_url' => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
				'ipn_pb_url' => 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr',
			]
		);

		return $gateways;
	}
);
