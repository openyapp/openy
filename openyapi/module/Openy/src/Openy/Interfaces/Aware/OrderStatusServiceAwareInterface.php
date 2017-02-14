<?php
namespace Openy\Interfaces\Aware;

use Openy\Interfaces\Service\OrderStatusServiceInterface;

interface OrderStatusServiceAwareInterface
{
    public function setOrderStatusService(OrderStatusServiceInterface $orderStatusService);

    public function getOrderStatusService();

}