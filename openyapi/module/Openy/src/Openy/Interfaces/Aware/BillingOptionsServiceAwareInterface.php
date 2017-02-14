<?php
/**
 * AwareInterface.
 * Current Billing Options Service Aware Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Config\Billing
 * @category Configuration
 * @see Zend\ServiceManager\ServiceLocatorAwareTrait
 * @see Openy\Interfaces\Properties\BillingOptionsInterface
 * @see Openy\Module 
 */
namespace Openy\Interfaces\Aware;

use Openy\Interfaces\Properties\BillingOptionsInterface;

/**
 * 
 * BillingOptionsServiceAwareInterface.
 * Defines getter and setter for BillingOptions Service 
 *
 * @uses \Openy\Interfaces\Properties\BillingOptionsInterface BillingOptions Property Interface
 * @uses \Openy\Options\BillingOptions
 *
 */
interface BillingOptionsServiceAwareInterface
	extends BillingOptionsInterface	        
{	
}