<?php
/**
 * Factory.
 * PaymentService Factory
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment
 * @category Payment
 * @see Openy\Module
 *
 */
namespace Openy\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Openy\Service\PaymentService;

/**
* PaymentServiceFactory.
* Factorizes PaymentService instance(s)
*
* @uses Openy\Service\PaymentService PaymentService
* @uses Openy\Interfaces\Service\PaymentServiceInterface PaymentServiceInterface
*
* @uses Zend\ServiceManager\FactoryInterface Zend Factory Interface
* @uses Zend\ServiceManager\ServiceLocatorInterface Zend ServiceLocator Interface
*/
class PaymentServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $stationService     = $sl->get('Openy\Service\OpyStation');
        $orderService       = $sl->get('Openy\Service\Order');
        $invoiceService     = $sl->get('Openy\Service\Invoice');
        $receiptService     = $sl->get('Openy\Service\Receipt');
        $paymentMapper      = $sl->get('Openy\Mapper\Payment');
        $currentUser        = $sl->get('Oauthreg\Service\CurrentUser');
        $options            = $sl->get('Openy\Service\OpenyOptions');
        $tpvoptions         = $sl->get('Openy\Service\TpvOptions');

        $paymentService = new PaymentService($orderService, $receiptService, $invoiceService, $stationService, $paymentMapper,$currentUser,$options,$tpvoptions);

        return $paymentService;

    }
}