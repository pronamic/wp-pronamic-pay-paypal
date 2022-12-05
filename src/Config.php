<?php
/**
 * Config
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\PayPal
 */

namespace Pronamic\WordPress\Pay\Gateways\PayPal;

use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Config
 *
 * @author  Remco Tolsma
 * @version 1.1.1
 * @since   1.0.0
 */
class Config extends GatewayConfig implements \JsonSerializable {
	/**
	 * The `webscr` URL.
	 *
	 * @var string
	 */
	private $webscr_url;

	/**
	 * The IPN post back URL.
	 *
	 * @var string
	 */
	private $ipn_pb_url;

	/**
	 * Email.
	 *
	 * @var string
	 */
	private $email;

	/**
	 * Construct config object.
	 *
	 * @param string $webscr_url The `webscr_url` URL.
	 * @param string $ipn_pb_url The IPN post back URL.
	 * @param string $email Email.
	 */
	public function __construct( $webscr_url, $ipn_pb_url, $email ) {
		$this->webscr_url = $webscr_url;
		$this->ipn_pb_url = $ipn_pb_url;
		$this->email      = $email;
	}

	/**
	 * Get the `webscr` URL.
	 * 
	 * @link https://developer.paypal.com/docs/paypal-payments-standard/integration-guide/formbasics/
	 * @return string
	 */
	public function get_webscr_url() {
		return $this->webscr_url;
	}

	/**
	 * Get the IPN post back URL.
	 * 
	 * @link https://developer.paypal.com/docs/api-basics/notifications/ipn/IPNImplementation/#specs
	 * @return string
	 */
	public function get_ipn_pb_url() {
		return $this->ipn_pb_url;
	}

	/**
	 * Get email.
	 *
	 * @return string
	 */
	public function get_email() {
		return $this->email;
	}

	/**
	 * JSON serialize.
	 *
	 * @return object
	 */
	public function jsonSerialize(): object {
		return (object) [
			'email' => $this->email,
		];
	}
}
