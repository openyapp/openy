<?php
namespace Oauthreg\Authentication\Factory;

use Oauthreg\Authentication\Listener\ClientIdAuthenticationListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ClientIdAuthenticationListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $name    = 'Oauthreg\Authentication\Adapter\ClientIdAuthentication';
        $adapter = $sl->get($name);

        $listener = new ClientIdAuthenticationListener($adapter); 
        return $listener;
    }
}