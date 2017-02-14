<?php

namespace Openy\V1\Rest\Receipt;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Openy\V1\Rest\Receipt\ReceiptMapper;

class ReceiptMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $adapterMaster  = $sl->get('dbMasterAdapter');
        $adapterSlave   = $sl->get('dbSlaveAdapter');
        $options        = $sl->get('Openy\Service\OpenyOptions');
        $currentUser    = $sl->get('Oauthreg\Service\CurrentUser');
        //$userPrefs      = new \Openy\V1\Rest\Preference\PreferenceEntity();
        return new ReceiptMapper($adapterMaster,$adapterSlave,$options,$currentUser);
    }
}