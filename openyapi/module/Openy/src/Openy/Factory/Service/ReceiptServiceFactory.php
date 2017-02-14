<?php
/**
 * Factory.
 * Receipt Service Factory
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment
 * @category Receipts
 * @see Openy\Module
 *
 */
namespace Openy\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Openy\Service\ReceiptService;

/**
 * ReceiptServiceFactory.
 * Receipt Service instance(s)
 *
 * @uses Openy\Service\ReceiptService Receipt Service class
 * @see \Openy\Interfaces\Service\ReceiptServiceInterface Receipt Service Interface
 * @uses Zend\ServiceManager\FactoryInterface Zend Factory Interface
 * @uses Zend\ServiceManager\ServiceLocatorInterface Zend ServiceLocator Interface
 */
class ReceiptServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $receiptMapper  = $sl->get('Openy\Mapper\Receipt');
        $options = $sl->get('Openy\Service\OpenyOptions');
        return new ReceiptService($receiptMapper,$options);
    }
}