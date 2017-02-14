<?php
/**
 * Interface.
 * Order Service Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Billing\Orders
 * @category Orders
 * @see Openy\Module
 *
 */
namespace Openy\Interfaces\Service;

use Openy\Model\Order\OrderEntity;

/**
 * OrderServiceInterface.
 * Defines functions for a Service managing Orders and their status
 *
 * @uses Openy\Model\Order\OrderEntity Order Entity
 * @see Openy\Service\OrderService Order Service class
 * @see Openy\Interfaces\Aware\OrderStatusServiceAwareInterface Order Status Aware Interface
 */
interface OrderServiceInterface
{

	/**
	 * Locates a order using its primary and unique key
	 * @param  OrderEntity $order   The order having initialized primary key attribute
	 * @return OrderEntity          Order populated with fetched data if any
	 */
	public function locateOrder(OrderEntity $order);

	/**
	 * Registers a new Order (persisting it in Repository), determining if is authorized to be delivered or not
	 *
	 * @param  OrderEntity $order       A prefilled order with the command to be delivered
	 * @param  String      $paymentUser UUid for the user who is gonna pay the order
	 * @return OrderEntity Order once registered
	 */
	public function prepareOrder(OrderEntity $order, $paymentUser = null);

	/**
	 * Registers a new order persisting it
	 * @param  OrderEntity $order Order to be registered
	 * @return OrderEntity        Order once registered
	 */
	public function registerOrder(OrderEntity $order);

	/**
	 * Performs necessary checks and operations to resolve if an order can be authorized
	 * If order is authorized this status change is persisted in repo
	 * @param OrderEntity $order  The order to be authorized
	 * @return OrderEntity The order entity once determined its authorization to be delivered
	 */
	public function authorizeOrder(OrderEntity $order);

	/**
	 * Cancels an order rolling back actions made and persisted objects
	 * @param  OrderEntity $order The order to be cancelled
	 * @return OrderEntity        The order once cancelled
	 */
	public function cancelOrder(OrderEntity $order);

	/**
	 * Performs necessary actions to deliver an authorized order and set it ready for payment
	 *
	 * @param  OrderEntity $order The order to be delivered
	 * @return OrderEntity        The order once delivered
	 */
	public function deliverOrder(OrderEntity $order);

	/**
	 * Pays an order and sets it as payed
	 * @param  OrderEntity $order 		The order filled at least with orderid, amount, and iduser
	 * @return OrderEntity              The order once payed
	 */
	public function payOrder(OrderEntity $order);

	/**
	 * Produces [or retreives] a receipt for a payed order
	 * @param  OrderEntity $order The order (already payed)
	 * @return \Openy\Model\Payment\ReceiptEntity | FALSE The receipt for given order if it has been payed or FALSE otherwise
	 */
	public function receiptOrder(OrderEntity $order);

	/**
	 * Produces [or retreives] an invoice for a payed order
	 * @param  OrderEntity $order The order (already payed)
	 * @return \Openy\Model\Invoice\InvoiceEntity | FALSE The invoice for given order receipt if order has been payed or FALSE otherwise
	 */
	public function invoiceOrder(OrderEntity $order);
}