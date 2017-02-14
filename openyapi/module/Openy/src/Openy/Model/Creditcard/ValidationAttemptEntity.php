<?php

namespace Openy\Model\Creditcard;

class ValidationAttemptEntity
{

	/**
	 * Creditcard being validated, necessary storage in order to cancell issued tpv tokens
	 * @var Uuid String
	 */
	public $idcreditcard = [];

	/**
	 * Attempts counter
	 * Counts how many attempts have been performed while trying to validate a credit card
	 * @var int
	 */
	public $attempts;

	/**
	 * Log for each attempt.
	 * Must containt a serialization of {client, ip|ipv6, token and timestamp}
	 * @var \Openy\Model\Classes\AccessEntity
	 */
	public $accesses = [];

	/**
	 * User who's attempting to validate a credit card
	 * @var Uuid String
	 */
	public $iduser;

	/**
	 * Last 4 digits for pan number
	 * @var String|Integer
	 */
	public $pan;

	/**
	 * Expiring date for the credit card
	 * @var Expiring date expressed as MM/YY
	 */
	public $expires;

	/**
	 * Timestamp for first validation attempt
	 * @var Datetime String
	 */
	public $start;

	/**
	 * Timestamp for last validation attempt
	 * @var Datetime String
	 */
	public $end;

	/**
	 * Validation date
	 * @var Datetime String
	 */
	public $validated;

}