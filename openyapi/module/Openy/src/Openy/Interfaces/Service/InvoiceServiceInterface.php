<?php
/**
 * Service Interface.
 * Contains interface for Invoicing Services
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Invoicing
 *
 */
namespace Openy\Interfaces\Service;

use Openy\Model\Payment\ReceiptCollection;
use Openy\Model\Invoice\InvoiceEntity;
use Openy\Interfaces\MapperInterface;
use Openy\Options\BillingOptions;
use Openy\V1\Rest\Preference\PreferenceEntity;

/**
 * Invoicing Services interface
 * Provides methods for:
 *
 * * Producing or locating an invoice given a collection of receipts
 * * Retrieving a collection of receipts from a given Invoice
 * 
 * @uses Openy\Model\Payment\ReceiptCollection Receipts Collection
 * @uses Openy\Model\Invoice\InvoiceEntity     Invoice Entity
 * @uses Openy\Interfaces\MapperInterface      Mapper Interface
 * @uses Openy\Options\BillingOptions		   Billing Options
 * @uses Openy\V1\Rest\Preference\PreferenceEntity (User) Preferences Entity
 * @see \Openy\Service\InvoiceService Invoice Service class 
 */
interface InvoiceServiceInterface
{

	/**
	 * Constructor
	 * @param MapperInterface $receiptMapper Mapper for handling Invoice receipts
	 * @param MapperInterface $invoiceMapper Mapper for handling Invoices
	 * @param unknown $currentUser			 Current (session) user entity
	 * @param PreferenceEntity $userPrefs	 Current (session) user Preferences Entity
	 * @param BillingOptions $billingOptions Openy billing Options
	 */
	public function __construct(
			MapperInterface $receiptMapper,
			MapperInterface $invoiceMapper,
			$currentUser,
			PreferenceEntity $userPrefs,
			BillingOptions $billingOptions
	);
	
	/**
	 * Get an Invoice from Receipts.
     * Produces an invoice for given receipts
	 * @param  ReceiptCollection $receipts Receipts to invoice
     * @param  String $date Invoice date with format 'Y-m-d'. Defaults to current one
     * @param  String $iduser Identifier for user who's gonna receive the invoice. Defaults to current session user
	 * @return InvoiceEntity|FALSE Invoice produced for the given receipts or FALSE on error or empty collection
     * @api
	 */
	public function getInvoice(ReceiptCollection $receipts, $date = null, $iduser = null);

    /**
     * Get Receipts from Invoice.
     * Returns all receipts collected (invoiced) in given invoice
     * @param  InvoiceEntity $invoice Invoice containing the receipts
     * @return ReceiptCollection $receipts Receipts invoiced in given invoice
     * @api
     */
    public function getReceipts(InvoiceEntity $invoice);

}