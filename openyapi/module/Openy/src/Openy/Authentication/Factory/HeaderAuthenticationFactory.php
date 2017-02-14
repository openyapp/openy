<?php
namespace Openy\Authentication\Factory;

use Openy\Authentication\Adapter\HeaderAuthentication;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HeaderAuthenticationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $request        = $sl->get('Request');
        $repository     = $sl->get('Oauthreg\V1\Rest\Clientregister\ClientregisterMapper');

        $adapter = new HeaderAuthentication($request, $repository);
        return $adapter;
    }
}