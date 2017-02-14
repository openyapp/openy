<?php
namespace Admin\Controller\Plugin;

use Admin\Controller\Plugin\AdminAcl;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class AclFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        $serviceManager = $pluginManager->getServiceLocator();
        return new AdminAcl();
    }
}