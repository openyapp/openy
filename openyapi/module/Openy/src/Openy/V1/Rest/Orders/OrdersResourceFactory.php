<?php
namespace Openy\V1\Rest\Orders;

class OrdersResourceFactory
{
    public function __invoke($services)
    {
    	$orderService = $services->get('Openy\Service\Order');
    	$orderMapper = $services->get('Openy\OrderMapper');
        return new OrdersResource($orderService,$orderMapper);
    }
}
