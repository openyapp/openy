<?php
/**
 * Interface.
 * Payment Service Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment
 * @category Payment
 * @see Openy\Module
 *
 */
namespace Openy\Interfaces\Service;

use Openy\Model\Order\OrderEntity;
use Openy\Model\Payment\PaymentEntity;
use Openy\Model\Payment\ReceiptEntity;
use Openy\Model\Payment\ReceiptCollection;

use Openy\Interfaces\Service\ReceiptServiceInterface;
use Openy\Interfaces\Service\InvoiceServiceInterface;
use Openy\Interfaces\Service\OpyStationServiceInterface;
use Openy\Interfaces\MapperInterface;
use Zend\Stdlib\AbstractOptions;
/**
 * PaymentServiceInterface.
 * Defines functions for a Service managing (orders') payments
 *
 * @uses Openy\Model\Order\OrderEntity Order Entity
 * @uses Openy\Model\Order\OrderCollection Orders Collection
 * @uses Openy\Model\Payment\PaymentEntity Payment Entity
 * @uses Openy\Model\Payment\ReceiptEntity Receipt Entity
 * @uses Openy\Model\Payment\ReceiptCollection Receipts Collection
 * @uses \Openy\V1\Rest\Invoice\InvoiceEntity Invoice Entity
 * @uses Openy\Interfaces\Service\OrderServiceInterface Orders Service Interface
 * @see \Openy\Service\OrderService Order Service class
 * @uses Openy\Interfaces\Service\ReceiptServiceInterface Receipts Service Interface
 * @see \Openy\Service\ReceiptService Receipts Service class
 * @uses Openy\Interfaces\Service\InvoiceServiceInterface Invoices Service Interface
 * @see \Openy\Service\InvoiceService Invoices Service class
 * @uses Openy\Interfaces\Service\OpyStationServiceInterface Openy Stations Service Interface
 * @see \Openy\Service\OpyStationService Openy Stations Service class
 */
interface PaymentServiceInterface			
{
	
	public function __construct(
			OrderServiceInterface $orderService,
			ReceiptServiceInterface $receiptService,
			InvoiceServiceInterface $invoiceService,
			OpyStationServiceInterface $stationService,
			MapperInterface $paymentMapper,
			$currentUser,
			AbstractOptions $options,
			AbstractOptions $tpvoptions
			);
	
	/**
	 * Check if an order could be payed by its user
	 * @param  OrderEntity $order The order to be authorized
	 * @return bool               True if order is authorized
	 */
	public function getOrderAuthorization(OrderEntity $order);

	/**
	 * Cancells an authorization if has been processed by POS (valid lastresponsecode)
	 * @param  OrderEntity $order The order to be cancelled
	 * @return bool               True if order has been correctly cancelled
	 */
	public function cancelOrderAuthorization(OrderEntity $order);

	/**
	 * Gets the matching payment for given order or creates a new payment if mismatch.
	 * If set by application config/user config, produces a receipt before returning the payment
	 * (and optionally an invoice)
	 * @param  OrderEntity &$order The order to be payed
	 * @return PaymentEntity  	   The payment created or matched
	 */
	public function getOrderPayment(OrderEntity $order);

	/**
	 * Gets the order what origined a given payment.
	 * @param  PaymentEntity $payment The Payment for searched order
	 * @return OrderEntity  	    The order matching the payment, having NULL on idorder if not found
	 */
	public function getPaymentOrder(PaymentEntity $payment);


	/**
	 * Locates a receipt for a given payment assigning and persisting its external identifier
	 * If payment does not have a receipt, it will be automatically generated
	 * @see  Openy\V1\Rest\Receipt\ReceiptEntity
	 * @param  PaymentEntity $payment The payment
	 * @param  string|null $receiptNumber External Id for the receipt. The actual number used by issuer
	 * @return ReceiptEntity|FALSE The receipt for the payment or FALSE if payment does not exists
	 */
	public function getReceipt(PaymentEntity $payment, $receiptNumber = null, $idopystation = null);

	/**
	 * Gets the matching payment for given receipt.
	 * @param  ReceiptEntity $receipt The receipt for searched payment
	 * @return PaymentEntity  	   The payment mathing the receipt, having NULL on idpayment if not found
	 */
	public function getReceiptPayment(ReceiptEntity $receipt);


	/**
	 * Locates a receipt for a given order assigning and persisting its external identifier
	 * @see  Openy\V1\Rest\Receipt\ReceiptEntity
	 *
	 * @param  OrderEntity $order         The order already payed
	 * @param  string|null $receiptNumber External Id for the receipt. The actual number used by issuer
	 * @return ReceiptEntity|FALSE The receipt for the order or FALSE if payment does not exists
	 */
	public function getOrderReceipt(OrderEntity $order);


	/**
	 * Gets the order what origined a given payment receipt.
	 * @param  ReceiptEntity $receipt The receipt for searched payment
	 * @return OrderEntity  	    The order matching the payment, having NULL on idorder if not found
	 */
	public function getReceiptOrder(ReceiptEntity $receipt);

	/**
	 * Gets the orders what origined a given payment receipts collection.
	 * @param  ReceiptCollection $receipts The receipts for searched payment orders
	 * @return \Openy\Model\Order\OrderCollection  The orders matching the payments. No error thrown even if counts differ
	 */
	public function getReceiptsOrders(ReceiptCollection $receipts);

	/**
	 * Gets an invoice from a collection of receipts
	 * @param  ReceiptCollection $receipts Receipts to be invoiced
	 * @return \Openy\V1\Rest\Invoice\InvoiceEntity|FALSE The invoice for the coll
	 */
	public function getInvoice(ReceiptCollection $receipts);

	/**
	 * Gets the invoice for a given receipt or creates a new invoice for just one receipt
	 * @param  ReceiptEntity $receipt been (or to be) invoiced
	 * @return \Openy\V1\Rest\Invoice\InvoiceEntity|FALSE The invoice for the payment
	 */
	public function getReceiptInvoice(ReceiptEntity $receipt);

	/**
	 * Gets an invoice for a given payment
	 * @param  CollectionInterface $receipts Receipts to be invoiced
	 * @return \Openy\V1\Rest\Invoice\InvoiceEntity|FALSE The invoice for the coll
	 */
	public function getOrderInvoice(OrderEntity $order);


	/**
	 * Gets an invoice for a given payment
	 * @param  PaymentEntity $payment to be invoiced
	 * @return \Openy\V1\Rest\Invoice\InvoiceEntity|FALSE The invoice for the payment
	 */
	public function getPaymentInvoice(PaymentEntity $payment);

}