<?php
/**
 * AwareTrait.
 * Current Billing Options Service Aware Trait
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Config\Billing
 * @category Configuration
 * @see Zend\ServiceManager\ServiceLocatorAwareTrait
 * @see Openy\Interfaces\Properties\BillingOptionsInterface
 * @see Openy\Module
 */
namespace Openy\Traits\Aware;

use Openy\Options\BillingOptions;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

use Openy\Traits\Properties\BillingOptionsTrait;


/**
 * BillingOptionsServiceAwareTrait.
 * Implements OptionsServiceAwareInterface
 *
 * @see  Openy\Interfaces\Aware\BillingOptionsServiceAwareInterface BillingOptions Service Aware Interface
 * @uses Openy\Traits\Properties\BillingOptionsTrait Billing Options Property Trait
 * @uses Openy\Options\BillingOptions Openy Billing Options class
 * @see  \Zend\ServiceManager\ServiceLocatorAwareInterface ServiceLocatorAwareInterface
 *
 */
trait BillingOptionsServiceAwareTrait
{
	use BillingOptionsTrait
	{
		getBillingOptions as inheritedGetBillingOptions;
	}
	
	/**
	 * Gets Openy Billing Options from ServiceLocator
	 * @return BillingOptions
	 * @see \Openy\Traits\Aware\BillingOptionsServiceAwareTrait BillingOptions Service Aware Trait 
	 * @uses \Openy\Traits\Properties\BillingOptionsTrait Billing Options Trait
	 * @uses \Zend\ServiceManager\ServiceLocatorInterface Zend ServiceLocator Interface
	 */
    public function getBillingOptions(){
    	$billingOptions = $this->inheritedGetBillingOptions();

    	if ($billingOptions instanceof BillingOptions)
    		return $billingOptions;
    	else{        
            if     (($this instanceof ServiceLocatorAwareInterface)
                || (property_exists($this, "serviceLocator")
                    && $this->serviceLocator instanceof ServiceLocatorInterface)
                )
                $this->setBillingOptions($this->serviceLocator->get('Openy\Service\BillingOptions'));
        }
        return $this->inheritedGetBillingOptions();
    }
    
}