<?php

namespace Openy\V1\Rest\Invoice;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Openy\V1\Rest\Invoice\InvoiceHydrator;

/**
 * @ignore
 */
class InvoiceHydratorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $receiptMapper  = $sl->get('Openy\V1\Rest\Receipt\ReceiptMapper');
        $options        = $sl->get('Openy\Service\OpenyOptions');
        return new InvoiceHydrator($receiptMapper,$options);
    }
}