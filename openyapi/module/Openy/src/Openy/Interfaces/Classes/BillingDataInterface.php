<?php
/**
 * BillingDataInterface.
 * Interface for entities with billing data containers
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Classes\Billing
 *
 */
namespace Openy\Interfaces\Classes;

/**
 * Interface for Billing Data Entity.
 * Stablishes interface functions for querying a Billing Data Object or Container (such Entities)
 *
 * @api
 * @see /phpdoc/packages/Openy.Invoicing.html Invoicing package
 * @see /phpdoc/packages/Openy.Orders.html Orders package
 *
 */
interface BillingDataInterface
{

	/**
	 * National ID number
	 * (must appear for issuer on receipts, and for issuer and payer in invoices)
	 * @return String
	 */
	public function getID();

	/**
	 * Name what must appear for issuer on receipts, and for issuer and payer in invoices)
	 * @return String
	 */
	public function getFullName();

	/**
	 * Address (including Zip Code and Country) what must appear for issuer and optionally for payer in invoices
	 * @return String
	 */
	public function getAddress();

	/**
	 * Optional issuer logo image filename what optionally may appear printed on receipts and invoices
	 * @return String
	 */
	public function getLogo();

	/**
	 * Optional phone number(s) what appears as contact info for issuer in receipts and invoices
	 * @return String
	 */
	public function getPhone();

	/**
	 * Optional mail address(es) what appears as contact info for issuer in receipts and invoices
	 * @return String
	 */
	public function getMail();

	/**
	 * Optional web uri what appears as contact info for issuer in receipts and invoices
	 * @return String
	 */
	public function getWeb();

	/**
	 * Reveals whenever an entity describes completeness enough for issuing or receiving billing documents
	 * @return boolean TRUE whenever the BillingDataEntity has Billing required atributes
	 */
	public function isComplete();
}
