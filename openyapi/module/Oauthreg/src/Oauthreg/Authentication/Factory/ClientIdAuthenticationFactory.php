<?php
namespace Oauthreg\Authentication\Factory;

use Oauthreg\Authentication\Adapter\ClientIdAuthentication;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ClientIdAuthenticationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $request        = $sl->get('Request');
        $repository     = $sl->get('Oauthreg\V1\Rest\Clientregister\ClientregisterMapper');

        $adapter = new ClientIdAuthentication($request, $repository);
        return $adapter;
    }
}