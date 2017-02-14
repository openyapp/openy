<?php
/**
 * Interface.
 * Order Status Service Interface
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
 * @uses Openy\Model\Order\OrderStatusEntity Order Status Entity
 * @see Openy\Service\OrderStatusService Order Status Service class
 * @see Openy\Interfaces\Aware\OrderStatusServiceAwareInterface Order Status Service Aware Interface
 */
interface OrderStatusServiceInterface
{
	/**
	 * Reveals if an Order can change (in any way) its status to another one or not
	 * @param  OrderEntity $order  The order with source status
	 * @param  Integer     $target The target status
	 * @return Boolean             True if the order can change its status to target or not
	 */
	public function canGetStatus(OrderEntity $order, $target);

	/**
	 * Retrieves the status of order payment (depending on method)
	 * @param  OrderEntity $order
	 * @return \Openy\Model\Order\OrderStatusEntity  The status entity populated with descriptive data
	 */
	public function getStatus(OrderEntity $order, $fetch_payment_status = TRUE);

}