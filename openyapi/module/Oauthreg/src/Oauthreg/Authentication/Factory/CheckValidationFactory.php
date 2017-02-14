<?php
namespace Oauthreg\Authentication\Factory;

use Oauthreg\Authentication\Adapter\CheckValidation;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CheckValidationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $request            = $sl->get('Request');
        $registerMapper     = $sl->get('Oauthreg\V1\Rest\Register\RegisterMapper');

        $adapter = new CheckValidation($request, $registerMapper);
        return $adapter;
    }
}