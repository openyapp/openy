<?php
namespace Opypos\Adapter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Opypos\Adapter\aadapterAdapter;

class aadapterAdapterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {

        $adapter = new aadapterAdapter();
        return $adapter;
    }
}