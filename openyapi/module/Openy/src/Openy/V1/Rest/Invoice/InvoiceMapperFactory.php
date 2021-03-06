<?php

namespace Openy\V1\Rest\Invoice;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Openy\V1\Rest\Invoice\InvoiceMapper;

/**
 * @ignore
 */
class InvoiceMapperFactory
    implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $adapterMaster  = $sl->get('dbMasterAdapter');
        $adapterSlave   = $sl->get('dbSlaveAdapter');
        $options        = $sl->get('Openy\Service\OpenyOptions');
        $currentUser    = $sl->get('Oauthreg\Service\CurrentUser');
        //$userPrefs      = new \Openy\V1\Rest\Preference\PreferenceEntity();
        $mapper = new InvoiceMapper($adapterMaster,$adapterSlave,$options,$currentUser);
        $FueltypesMapper = $sl->get('Openy\V1\Rest\Fueltype\FueltypeMapper');
        $mapper->setFuelTypes($FueltypesMapper->fetchAll([]));
        $InvoiceHydrator = $sl->get('Openy\V1\Rest\Invoice\InvoiceHydrator');
        $mapper->setInvoiceHydrator($InvoiceHydrator);

        return $mapper;
    }
}