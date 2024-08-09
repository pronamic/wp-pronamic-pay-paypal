<?php
/**
 * Statuses
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2024 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\PayPal
 */

namespace Pronamic\WordPress\Pay\Gateways\PayPal;

use Pronamic\WordPress\Pay\Payments\PaymentStatus as Core_Statuses;

/**
 * Statuses
 *
 * @author  Reüel van der Steege
 * @version 1.0.0
 * @since   1.0.0
 */
class Statuses {
	/**
	 * Canceled Reversal.
	 *
	 * @var string
	 */
	const CANCELED_REVERSAL = 'Canceled_Reversal';

	/**
	 * Completed.
	 *
	 * @var string
	 */
	const COMPLETED = 'Completed';

	/**
	 * Created.
	 *
	 * @var string
	 */
	const CREATED = 'Created';

	/**
	 * Denied.
	 *
	 * @var string
	 */
	const DENIED = 'Denied';

	/**
	 * Expired.
	 *
	 * @var string
	 */
	const EXPIRED = 'Expired';

	/**
	 * Failed.
	 *
	 * @var string
	 */
	const FAILED = 'Failed';

	/**
	 * Pending.
	 *
	 * @var string
	 */
	const PENDING = 'Pending';

	/**
	 * Refunded.
	 *
	 * @var string
	 */
	const REFUNDED = 'Refunded';

	/**
	 * Reversed.
	 *
	 * @var string
	 */
	const REVERSED = 'Reversed';

	/**
	 * Processed.
	 *
	 * @var string
	 */
	const PROCESSED = 'Processed';

	/**
	 * Voided.
	 *
	 * @var string
	 */
	const VOIDED = 'Voided';

	/**
	 * Transform a PayPal state to a more global status.
	 *
	 * @param string $status PayPal status.
	 * @return string|null
	 */
	public static function transform( $status ) {
		switch ( $status ) {
			case self::COMPLETED:
				return Core_Statuses::SUCCESS;

			case self::DENIED:
			case self::FAILED:
				return Core_Statuses::FAILURE;

			case self::CREATED:
			case self::PENDING:
				return Core_Statuses::OPEN;

			default:
				return null;
		}
	}
}
