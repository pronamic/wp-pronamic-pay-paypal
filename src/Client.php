<?php
/**
 * PayPal client
 *
 * @author Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license GPL-3.0-or-later
 * @package Pronamic\WordPress\Pay\Gateways\PayPal
 */

namespace Pronamic\WordPress\Pay\Gateways\PayPal;

use Pronamic\WordPress\Http\Facades\Http;

/**
 * PayPal client
 *
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class Client {
	/**
	 * Config.
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Constructs and initializes an PayPal client object.
	 *
	 * @param Config $config PayPal config.
	 */
	public function __construct( Config $config ) {
		$this->config = $config;
	}

	/**
	 * Validate Payment Data Transfer (PDT) notification.
	 *
	 * @param array $data Request data.
	 * @return bool
	 */
	public function validate_notification( $data ) {
		$url = \add_query_arg(
			$data,
			$this->config->get_ipn_pb_url() . '?cmd=_notify-validate'
		);

		/*
		 * Request notification validation.
		 *
		 * Note: A delay in processing by PayPal can result in 'Bad Request' response,
		 * even though payment has been completed successfully.
		 */
		$request = Http::get( $url );

		// Check response body.
		return NotificationValidationStatuses::VERIFIED === $request->body();
	}
}
