<?php

namespace Openy\Factory\Mapper\SOAP;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Openy\Model\Tpv\SOAP\ResponseMapper;
//use Openy\Service\Company as CompanyService;

class ResponseMapperFactory
	implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $adapterMaster  = $sl->get('dbMasterAdapter');
        $adapterSlave   = $sl->get('dbSlaveAdapter');
        $options        = $sl->get('Openy\Service\OpenyOptions');
        $currentUser    = $sl->get('Oauthreg\Service\CurrentUser');
        return new ResponseMapper($adapterMaster, $adapterSlave, $options, $currentUser);
    }
}