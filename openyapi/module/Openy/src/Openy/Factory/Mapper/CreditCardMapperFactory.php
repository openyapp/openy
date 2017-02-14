<?php

namespace Openy\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Openy\V1\Rest\Creditcard\CreditcardMapper;

class CreditCardMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $adapterMaster  = $sl->get('dbMasterAdapter');
        $adapterSlave   = $sl->get('dbSlaveAdapter');
        $options        = $sl->get('Openy\Service\OpenyOptions');
        $currentUser    = $sl->get('Oauthreg\Service\CurrentUser');
		$tpvoptions     = $sl->get('Openy\Service\TpvOptions');
		$prefs 			= $sl->get('Openy\Service\CurrentPreferences');
        $mapper = new CreditcardMapper($adapterMaster, $adapterSlave, $options, $currentUser, $tpvoptions);
        $mapper->setPreferences($prefs);
        return $mapper;

    }
}