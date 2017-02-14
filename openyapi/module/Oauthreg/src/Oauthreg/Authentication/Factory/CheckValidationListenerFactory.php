<?php
namespace Oauthreg\Authentication\Factory;

use Oauthreg\Authentication\Listener\CheckValidationListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CheckValidationListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $name    = 'Oauthreg\Authentication\Adapter\CheckValidation';
        $adapter = $sl->get($name);

        $listener = new CheckValidationListener($adapter);
        return $listener;
    }
}