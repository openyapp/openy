<?php
namespace Openy\Traits\Aware;

use Openy\Interfaces\Service\OrderStatusServiceInterface;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

trait OrderStatusServiceAwareTrait
{
	/**
	 * Order Status Service Property
	 * @var OrderStatusServiceInterface
	 * @see \Openy\Traits\Aware\OrderStatusServiceAwareTrait OrderStatusServiceAwareTrait
	 */
	protected $orderStatusService;

    public function setOrderStatusService(OrderStatusServiceInterface $orderStatusService){
        $this->orderStatusService = $orderStatusService;
        return $this;
    }

    public function getOrderStatusService(){
        if     (($this instanceof ServiceLocatorAwareInterface)
            || (property_exists($this, "serviceLocator")
                && $this->serviceLocator instanceof ServiceLocatorInterface)
            )
        {
            $this->orderStatusService = $this->orderStatusService ? : $this->serviceLocator->get('Openy\Service\OrderStatus');
        }
        return $this->orderStatusService;
    }

}