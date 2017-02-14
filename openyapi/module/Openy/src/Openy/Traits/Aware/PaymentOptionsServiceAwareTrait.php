<?php
/**
 * AwareTrait.
 * Payment Options Service Aware Trait
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment
 * @category Configuration
 * @see Zend\ServiceManager\ServiceLocatorAwareTrait
 * @see Openy\Interfaces\Aware\PaymentOptionsServiceAwareInterface
 * @see Openy\Module
 */
namespace Openy\Traits\Aware;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Openy\Options\PaymentOptions;
use Openy\Traits\Properties\PaymentOptionsTrait;

/**
 * PaymentOptionsServiceAwaretrait..
 * Implements PaymentOptionsServiceAwareInterface
 *
 * @see Openy\Interfaces\Aware\PaymentOptionsServiceAwareInterface
 * @uses Openy\Options\PaymentOptions Payment Options class
 * @uses Openy\Traits\Properties\PaymentOptionsTrait Payment Options Trait
 * @see  \Zend\ServiceManager\ServiceLocatorAwareInterface ServiceLocatorAwareInterface 
 *
 */
trait PaymentOptionsServiceAwareTrait
{

	use PaymentOptionsTrait{
		getPaymentOptions as inheritedGetPaymentOptions;
	}
	
	/**
	 * Gets Payment Options from ServiceLocator
	 * @return PaymentOptions
	 * @see \Openy\Traits\Aware\PaymentOptionsServiceAwareTrait PaymentOptions Service Aware Trait
	 * @uses \Openy\Traits\Properties\PaymentOptionsTrait Payment Options Trait
	 * @uses \Zend\ServiceManager\ServiceLocatorInterface Zend ServiceLocator Interface
	 */
	public function getPaymentOptions(){
		$paymentOptions = $this->inheritedGetPaymentOptions();
	
		if ($paymentOptions instanceof PaymentOptions)
			return $paymentOptions;
		else{
			if     (($this instanceof ServiceLocatorAwareInterface)
					|| (property_exists($this, "serviceLocator")
							&& $this->serviceLocator instanceof ServiceLocatorInterface)
			)
				$this->setPaymentOptions($this->serviceLocator->get('Openy\Service\PaymentOptions'));
		}
		return $this->inheritedGetPaymentOptions();
	}	
}