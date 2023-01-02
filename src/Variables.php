<?php
/**
 * Variables
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\PayPal
 */

namespace Pronamic\WordPress\Pay\Gateways\PayPal;

/**
 * Variables
 *
 * @author  Remco Tolsma
 * @version 1.1.1
 * @since   1.0.0
 */
class Variables {
	/**
	 * Variables.
	 *
	 * @var array<string, string>
	 */
	private $variables;

	/**
	 * Construct variables object.
	 */
	public function __construct() {
		$this->variables = [];
	}

	/**
	 * Set business.
	 *
	 * @param string $email Email.
	 * @return void
	 */
	public function set_business( $email ) {
		$this->set_value( 'business', $email );
	}

	/**
	 * Specifying button type — cmd
	 *
	 * The cmd variable is always required in a FORM. Its value determines which
	 * PayPal Payments Standard checkout experience you are using to obtain payment.
	 *
	 * @link https://developer.paypal.com/docs/paypal-payments-standard/integration-guide/formbasics/?mark=cmd#specifying-button-type--cmd
	 * @param string $cmd Cmd.
	 * @return void
	 */
	public function set_cmd( $cmd ) {
		$this->set_value( 'cmd', $cmd );
	}

	/**
	 * The Cart Upload command for third-party carts.
	 *
	 * @link https://developer.paypal.com/docs/paypal-payments-standard/integration-guide/cart-upload/
	 * @param bool $upload Upload.
	 * @return void
	 */
	public function set_upload( $upload ) {
		$this->set_value( 'upload', true === $upload ? '1' : '0' );
	}

	/**
	 * Set value.
	 *
	 * @param string $key   Key.
	 * @param string $value Value.
	 * @return void
	 */
	public function set_value( $key, $value ) {
		$this->variables[ $key ] = $value;
	}

	/**
	 * Set value.
	 *
	 * @param string      $key   Key.
	 * @param string|null $value Value.
	 * @return void
	 */
	public function set_optional_value( $key, $value ) {
		if ( null !== $value ) {
			$this->set_value( $key, $value );
		}
	}

	/**
	 * Get array.
	 *
	 * @return array<string, string>
	 */
	public function get_array() {
		return $this->variables;
	}
}
