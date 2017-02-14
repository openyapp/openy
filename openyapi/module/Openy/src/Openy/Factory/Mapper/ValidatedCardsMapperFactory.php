<?php

namespace Openy\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Openy\Model\Creditcard\ValidatedCardsMapper;


class ValidatedCardsMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $adapterMaster  = $sl->get('dbMasterAdapter');
        $adapterSlave   = $sl->get('dbSlaveAdapter');
        $options        = $sl->get('Openy\Service\OpenyOptions');
        $currentUser    = $sl->get('Oauthreg\Service\CurrentUser');
        $tpvOptions		= $sl->get('Openy\Service\TpvOptions');
        $prefs 			= $sl->get('Openy\Service\CurrentPreferences');
        $mapper = new ValidatedCardsMapper($adapterMaster, $adapterSlave, $options, $currentUser,$tpvOptions);
        $mapper->setPreferences($prefs);
        return $mapper;
    }
}