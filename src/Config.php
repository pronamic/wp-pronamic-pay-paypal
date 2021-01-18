<?php
/**
 * Config
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
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
	 * Email.
	 *
	 * @var string
	 */
	private $email;

	/**
	 * Construct config object.
	 *
	 * @param string $mode  Mode.
	 * @param string $email Email.
	 */
	public function __construct( $mode, $email ) {
		$this->mode  = $mode;
		$this->email = $email;
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
	public function jsonSerialize() {
		return (object) array(
			'mode'  => $this->mode,
			'email' => $this->email,
		);
	}
}
