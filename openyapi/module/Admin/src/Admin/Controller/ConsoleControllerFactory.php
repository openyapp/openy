<?php

namespace Admin\Controller;

use Admin\Controller\ConsoleController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConsoleControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();
        $console        = $serviceLocator->get('console');
        $priceMapper    = $serviceLocator->get('Admin\Model\PriceMapper');
        $stationMapper  = $serviceLocator->get('Admin\Model\StationMapper');
        $apicaller      = $serviceLocator->get('ElemApicaller\Service');
//         $appConfig      = $serviceLocator->get('config');

        return new ConsoleController($console, $priceMapper, $stationMapper, $apicaller);
    }
}
