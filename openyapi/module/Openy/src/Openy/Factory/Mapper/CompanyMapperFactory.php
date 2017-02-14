<?php

namespace Openy\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
//use Openy\V1\Rest\Company\CompanyMapper;
use Openy\Model\Company\CompanyMapper;

class CompanyMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $adapterMaster  = $sl->get('dbMasterAdapter');
        $adapterSlave   = $sl->get('dbSlaveAdapter');
        $options        = $sl->get('Openy\Service\OpenyOptions');
        $currentUser    = $sl->get('Oauthreg\Service\CurrentUser');
        return new CompanyMapper($adapterMaster, $adapterSlave, $options, $currentUser);
    }
}