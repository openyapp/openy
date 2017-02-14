<?php
/**
 * AwareInterface.
 * Payment Options Service Aware Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment
 * @category Configuration
 * @see Zend\ServiceManager\ServiceLocatorAwareTrait
 * @see Openy\Interfaces\Properties\PaymentOptionsInterface
 * @see Openy\Module 
 */
namespace Openy\Interfaces\Aware;

use Openy\Interfaces\Properties\PaymentOptionsInterface;

/**
 * 
 * PaymentOptionsServiceAwareInterface.
 * Defines getter and setter for PaymentOptions Service 
 *
 * @uses \Openy\Interfaces\Properties\PaymentOptionsInterface PaymentOptions Property Interface
 * @uses \Openy\Options\PaymentOptions
 *
 */
interface PaymentOptionsServiceAwareInterface
	extends PaymentOptionsInterface	        
{	
}