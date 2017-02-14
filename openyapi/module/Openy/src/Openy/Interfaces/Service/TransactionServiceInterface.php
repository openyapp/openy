<?php
/**
 * Interface.
 * Transaction Service Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment\POS
 * @category Transaction
 * @see Openy\Module
 *
 */
namespace Openy\Interfaces\Service;

use Openy\Model\Order\OrderEntity;
use Openy\Model\Payment\PaymentEntity;
use Openy\Model\Transaction\TransactionEntity;
use Openy\Model\Tpv\SOAP\ResponseEntity;

/**
 * TransactionServiceInterface.
 * Defines functions for a Service managing POS payment transactions
 *
 * @uses Openy\Model\Order\OrderEntity Order Entity
 * @uses Openy\Model\Payment\PaymentEntity Payment Entity
 * @uses Openy\Model\Transaction\TransactionEntity Transaction Entity
 * @uses Openy\Model\Tpv\SOAP\ResponseEntity POS Transaction SOAP Response Entity   
 * @see  \Openy\Interfaces\Service\SOAPServiceInterface SOAP Service Interface
 * @uses \Openy\Model\Classes\MessageEntity Transaction Status Message Entity 
 */
interface TransactionServiceInterface
{
	/**
	 * Sends a transaction to Bank POS requesting for its approval
	 * @param  TransactionEntity $transaction The transaction to be authorized
	 *                                        if authorized, and token been required, trasnaction is populated with retreived token
	 * @return bool                           True if transaction has been approved by Bank POS
	 */
	public function getAuthorization(TransactionEntity &$transaction);

	/**
	 * Confirms a previously authorized transaction
	 * @param  TransactionEntity $transaction 	The transaction to be confirmed (committed)
	 * @return bool               				True if transaction commit has succeeded
	 */
	public function confirmAuthorization(TransactionEntity $transaction);


	/**
	 * Cancells a previously authorized transaction
	 * @param  TransactionEntity $transaction 	The transaction to be cancelled (rolled back)
	 * @return bool               				True if transaction roll back has succeeded
	 */
	public function cancellAuthorization(TransactionEntity $transaction);

	/**
	 * Refunds the amount in transaction if it has a previous auth
	 * @param  TransactionEntity $transaction The transaction containing the amount to be refunded
	 * @return Bool                           True if refund has been performed, false otherwise
	 */
	public function refundAuthorization(TransactionEntity $transaction);

	/**
	 * Sends a transaction to Bank POS requesting for a payment to be charged to a credit card
	 * @param  TransactionEntity $transaction 	The transaction containing the payment data
	 * @return bool               				True if transaction payment has succeeded
	 */
	public function payAuthorization(TransactionEntity $transaction);

	/**
	 * Given a payment finds its transaction
	 * @return [type] [description]
	 */
	public function getPaymentTransaction(PaymentEntity $payment);

	/**
	 * Given an order finds all related transactions
	 * @return \Zend\Paginator\Paginator Collection containing Order related trasnactions
	 */
	public function getOrderRelatedTransactions(OrderEntity $order);

	/**
	 * Prepares a transaction instance like an order
	 * @param  OrderEntity $order Order to be cast to transaction
	 * @return TransactionEntity  Transaction generated from order
	 */
	public function getTransactionFromOrder(OrderEntity $order, $fetch_from_repo = FALSE);
	

	/**
	 * Returns a transaction with the persisted data, matching given transaction
	 * @param  TransactionEntity $transaction The transasction to locate and fetch from repository
	 * @return TransactionEntity              The persisted transaction
	 */
	public function fetchTransaction(TransactionEntity $transaction);


	/**
	 * Gets the status (error) for a (failed) transaction
	 * @param  TransactionEntity $transaction The transaction with status to be queried
	 * @return \Openy\Model\Classes\MessageEntity Message containing status data
	 */
	public function getTransactionStatus(TransactionEntity $transaction);

	/**
	 * Updates transaction status (setting from a given response) and persist it
	 * @param  TransactionEntity $transaction The transaction to be updated
	 * @param  ResponseEntity    $response    The response from withing take the status info
	 * @return TransactionEntity              The updated transaction once persisted
	 */
	public function updateTransactionStatus(TransactionEntity $transaction, ResponseEntity $response);

}
