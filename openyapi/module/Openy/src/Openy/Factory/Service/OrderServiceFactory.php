<?php
/**
 * Factory.
 * Order Service Factory
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Billing\Orders
 * @category Orders
 * @see Openy\Module
 *
 */
namespace Openy\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Openy\Service\OrderService;

/**
 * OrderServiceFactory.
 * Factorizes OrderService instance(s)
 *
 * @uses Openy\Service\OrderService OrderService
 * @uses Openy\Interfaces\Service\OrderServiceInterface OrderServiceInterface
 *
 * @uses Zend\ServiceManager\FactoryInterface Zend Factory Interface
 * @uses Zend\ServiceManager\ServiceLocatorInterface Zend ServiceLocator Interface
 */
class OrderServiceFactory implements FactoryInterface
{
	/**
	 * {@inheritDoc}
	 * @see \Zend\ServiceManager\FactoryInterface::createService()
	 * @return \Openy\Interfaces\Service\OrderServiceInterface
	 */
    public function createService(ServiceLocatorInterface $sl)
    {
        $orderMapper        = $sl->get('Openy\OrderMapper');
        $currentUser        = $sl->get('Oauthreg\Service\CurrentUser');
        $userPrefs          = $sl->get('CurrentUserPreferences');
        $options            = $sl->get('Openy\Service\OpenyOptions');
        return new OrderService($orderMapper, $currentUser, $userPrefs, $options);
    }
}