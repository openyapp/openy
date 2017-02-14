<?php
/**
 * Interface.
 * Receipt Service Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment
 * @category Receipts
 * @see Openy\Module
 *
 */
namespace Openy\Interfaces\Service;

use Openy\Model\Payment\ReceiptEntity;

/**
 * ReceiptServiceInterface.
 * Defines functions for a Service managing payment Receipts 
 *
 * @uses Openy\Model\Payment\ReceiptEntity Receipt Entity
 * @see \Openy\Model\Payment\ReceiptCollection Receipts Collection
 * @see \Openy\V1\Rest\Invoice\InvoiceEntity Invoice Entity
 * @see Openy\Service\ReceiptService Openy Receipt Service class
 * @see \Openy\Interfaces\Service\PaymentService\Interface Payment Service Interface
 * @see \Openy\Service\PaymentService Payment Service class
 *
 */
interface ReceiptServiceInterface
{

	/**
	 * Locates a receipt using its primary and unique keys
	 * @param  ReceiptEntity $receipt The receipt containing valued attributes helphing the location
	 * @return ReceiptEntity          Incoming receipt populated with fetched data if any
	 */
	public function locateReceipt(ReceiptEntity $receipt);

	/**
	 * Persists a receipt or fetches it if already persisted
	 * @param  ReceiptEntity $receipt The receipt to fetch or to persist
	 * @return ReceiptEntity|\Openy\Model\Classes\MessageEntity The receipt once persisted or fetched or message reporting Repository problem
	 */
	public function getReceipt(ReceiptEntity $receipt);

	/**
	 * Persists given receipt info locating it first
	 * @param  ReceiptEntity $receipt The receipt containing the data to be persisted
	 * @return ReceiptEntity          The receipt populated with the data once persisted, or an empty Receipt if not found on repository
	 */
	public function alterReceipt(ReceiptEntity $receipt);

	/**
	 * Reveals if a receipt has a default temporary outer number, or it is an ACTUAL|REAL one
	 * @param  ReceiptEntity $receipt [description]
	 * @return boolean                [description]
	 */
	public function hasTemporaryNumber(ReceiptEntity $receipt);

}