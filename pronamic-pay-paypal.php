<?php
/**
 * Plugin Name: Pronamic Pay PayPal Add-On
 * Plugin URI: https://www.pronamic.eu/plugins/pronamic-pay-paypal/
 * Description: Extend the Pronamic Pay plugin with the PayPal gateway to receive payments with PayPal through a variety of WordPress plugins.
 *
 * Version: 1.0.0
 * Requires at least: 4.7
 *
 * Author: Pronamic
 * Author URI: https://www.pronamic.eu/
 *
 * Text Domain: pronamic-pay-paypal
 * Domain Path: /languages/
 *
 * License: GPL-3.0-or-later
 *
 * Depends: wp-pay/core
 *
 * GitHub URI: https://github.com/wp-pay-gateways/paypal
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\PayPal
 */

add_filter(
	'pronamic_pay_gateways',
	function( $gateways ) {
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\PayPal\Integration();

		return $gateways;
	}
);
