<?php

namespace Openy\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Openy\Model\Tpv\SoapMapper;
//use Openy\Service\Company as CompanyService;

class SoapMapperFactory
	implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
//        $adapterMaster  = $sl->get('dbMasterAdapter');
//        $adapterSlave   = $sl->get('dbSlaveAdapter');
//        $currentUser    = $sl->get('Oauthreg\Service\CurrentUser');
        $tpvoptions        = $sl->get('Openy\Service\TpvOptions');
        return new SoapMapper($tpvoptions);
    }
}