<?php

namespace Openy\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Openy\Model\Order\OrderMapper;

class OrderMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $adapterMaster  = $sl->get('dbMasterAdapter');
        $adapterSlave   = $sl->get('dbSlaveAdapter');
        $options        = $sl->get('Openy\Service\OpenyOptions');
        $currentUser    = $sl->get('Oauthreg\Service\CurrentUser');
        $userPrefs          = new \Openy\V1\Rest\Preference\PreferenceEntity();
        return new OrderMapper($adapterMaster, $adapterSlave, $options, $currentUser, $userPrefs);
    }
}