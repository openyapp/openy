<?php
/**
 * Factory.
 * Invoice Service Factory
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Invoicing
 * @category Invoices
 * @see Openy\Module
 *
 */
namespace Openy\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Openy\Service\InvoiceService;

/**
 * InvoiceServiceFactory.
 * Invoice Service instance(s)
 *
 * @uses Openy\Service\InvoiceService Invoice Service class
 * @see \Openy\Interfaces\Service\InvoiceServiceInterface Invoice Service Interface
 * @uses Zend\ServiceManager\FactoryInterface Zend Factory Interface
 * @uses Zend\ServiceManager\ServiceLocatorInterface Zend ServiceLocator Interface
 */
class InvoiceServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $receiptMapper  = $sl->get('Openy\Mapper\Receipt');        
        $invoiceMapper  = $sl->get('Openy\Mapper\Invoice');
        $currentUser    = $sl->get('Oauthreg\Service\CurrentUser');
        $userPrefs      = $sl->get('CurrentUserPreferences');
        $billingOptions = $sl->get('Openy\Service\BillingOptions');
        
        return new InvoiceService($receiptMapper, $invoiceMapper,$currentUser, $userPrefs,$billingOptions);
    }
}