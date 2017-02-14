<?php
/**
 * Trait.
 * Payment Service Aware Trait
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment
 * @category Payment
 * @see Openy\Module
 *
 */
namespace Openy\Traits\Aware;

use Openy\Interfaces\Service\PaymentServiceInterface;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * PaymentServiceAwareTrait.
 * Implements PaymentServiceAwareInterface
 *
 * @see \Openy\Interfaces\Aware\PaymentServiceAwareInterface PaymentServiceAwareInterface
 * @uses Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
 * @uses Zend\ServiceManager\ServiceLocatorAwareInterface Zend ServiceLocator Aware Interface
 * @uses Zend\ServiceManager\ServiceLocatorInterface      Zend ServiceLocator Interface
 *
 */
trait PaymentServiceAwareTrait
{

	/**
	 * Payment service property
	 * @var PaymentServiceInterface
	 */
	protected $paymentService;

    public function setPaymentService(PaymentServiceInterface $paymentService){
        $this->paymentService = $paymentService;
        return $this;
    }

    public function getPaymentService(){
        if     (($this instanceof ServiceLocatorAwareInterface)
            || (property_exists($this, "serviceLocator")
                && $this->serviceLocator instanceof ServiceLocatorInterface)
                )
        {
            $this->paymentService = $this->paymentService ? : $this->getServiceLocator()->get('Openy\Service\Payment');
        }
        return $this->paymentService;
    }

}