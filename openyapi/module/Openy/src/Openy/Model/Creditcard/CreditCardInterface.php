<?php

namespace Openy\Model\CreditCard;

interface CreditCardDataInterface
{
	public $pan;
	public $cvv;
	public $expiry;
	public $identifier;
}

interface CreditCardInterface
{

	const REQUIRED = 'REQUIRED';

	/**
	 * Credit Card identifier data
	 * @var CreditCardDataInterface
	 */
	protected $data;

	public function __construct(CreditCardInterface $cardData);


	/**
	 * CVV code
	 * @return String
	 */
	public function getCvv();


	/**
	 * Expiry date
	 * @return String 
	 */
	public function getExpiry();

	/**
	 * Credit Card PAN number
	 * @return String|integer (12)
	 */
	public function getPan();


	/**
	 * Identifier for a given card.
	 * It may depend on Merchant Group
	 * 
	 * @param string Merchant group or null if default
	 * @return string | REQUIRED if not identifier has been registered
	 */
	public function getIdentifier($merchant_group = null);

	/**
	 * Merchant groups where a card is registered
	 *
	 * @return  Array MerchantGroups if any, or default Merchant Group
	 */
	public function getMerchantGroups();

}