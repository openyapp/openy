<?php
/**
 * Interface.
 * Payment Service Aware Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment
 * @category Payment
 * @see Openy\Module
 *
 */
namespace Openy\Interfaces\Aware;

use Openy\Interfaces\Service\PaymentServiceInterface;

/**
 * PaymentServiceAwareInterface.
 * Defines getter and setter functions for a Payment Service property
 *
 * @uses Openy\Interfaces\Service\PaymentServiceInterface Payment Service Interface
 * @see  \Openy\Service\PaymentService Payment Service
 * @see \Zend\ServiceManger\ServiceLocatorAwareInterface Zend ServiceLocator Aware Interface
 */
interface PaymentServiceAwareInterface
{
	/**
	 * Sets payment service property
	 * @param PaymentServiceInterface $TransactionService
	 * @return PaymentServiceAwareInterface Current Instance 
	 */
    public function setPaymentService(PaymentServiceInterface $paymentService);
	
    /**
     * Gets payment service property
     * @return PaymentServiceInterface
     */
    public function getPaymentService();
}