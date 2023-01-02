<?php
/**
 * Notification validation statuses
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\PayPal
 */

namespace Pronamic\WordPress\Pay\Gateways\PayPal;

/**
 * Notification validation statuses
 *
 * @author  Reüel van der Steege
 * @version 1.1.1
 * @since   1.0.0
 */
class NotificationValidationStatuses {
	/**
	 * Verified.
	 *
	 * @var string
	 */
	const VERIFIED = 'VERIFIED';

	/**
	 * Invalid.
	 *
	 * @var string
	 */
	const INVALID = 'INVALID';
}
