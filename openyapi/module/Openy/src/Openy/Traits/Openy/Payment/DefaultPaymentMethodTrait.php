<?php
/**
* Default Payment Method Trait.
* Provides getters for default method and id to be used in payments
*
* @author XSubira <xsubira@openy.es>
* @package Openy\Preferences\Traits\Payment
* @see Openy\Interfaces\Properties\DefaultPaymentMethodInterface
* @see Openy\Service\OrderService
* @see Openy\Service\PaymentService
*
*/
namespace Openy\Traits\Openy\Payment;

use Openy\Interfaces\Properties\OptionsInterface;
use Openy\Interfaces\Aware\PreferenceAwareInterface;

/**
 * Default Payment Method Trait.
 * Implements DefaultPaymentMethodInterface
 *
 * @see  \Openy\Interfaces\Properties\DefaultPaymentMethodInterface DefaultPaymentMethodInterface
 * @uses Openy\Interfaces\Aware\PreferenceAwareInterface Preferences Aware Interface 
 * @uses Openy\Interfaces\Properties\OptionsInterface Options Property Interface
 *
 */
trait DefaultPaymentMethodTrait
{

	/**
	 * User's selected payment method.
	 * @var Integer
	 * @see \Openy\Traits\Properties\DefaultPaymentMethodTrait Default Payment Method Trait 
	 */
	private $userDefaultPaymentMethod;
	
	/**
	 * User's default payment method identifier.
	 * e.g. id for user's default credit card
	 * @var String
	 * @see \Openy\Traits\Properties\DefaultPaymentMethodTrait Default Payment Method Trait 
	 */
	private $userDefaultPaymentMethodId;
	
	/**
	 * Openy Default Payment Method.
	 * @var Integer
	 * @see \Openy\Traits\Properties\DefaultPaymentMethodTrait Default Payment Method Trait
	 */
	private $openyOptionsDefaultPaymentMethod; 
	
	/**
	 * Returns the value (integer) for default payment method
	 * @param  boolean $getKey If true, forces to return the name of the payment method instead of its value
	 * @return int|string
	 * @see \Openy\Traits\Properties\DefaultPaymentMethodTrait Default Payment Method Trait 
	 */	
	public function getDefaultPaymentMethod($getKey = false){
		
		$userDefaultMethod = $this->getUserDefaultPaymentMethod();		
		$openyDefaultMethod = $this->getOpenyOptionsDefaultPaymentMethod();

		//User chosen payment method has preference over openy
		$result = $userDefaultMethod ? : $openyDefaultMethod;

		if ($getKey){
			$methods = $this->getOpenyPaymentMethods();
			$result = array_flip($methods)[$result];
		}

		return $result;
	}

		/**
		 * Gets the user default payment method
		 * @return Integer|NULL If any, returns the user default payment method configured in his or her preferences
	 	 * @see \Openy\Traits\Properties\DefaultPaymentMethodTrait Default Payment Method Trait 
		 */
		private function getUserDefaultPaymentMethod(){
			if (is_null($this->userDefaultPaymentMethod)){
				$userPrefs = NULL;			
				
				if ($this instanceof PreferenceAwareInterface)
					$userPrefs = $this->getUserPrefs();
				
				if ($userPrefs && property_exists($userPrefs, "default_payment_method"))
					$this->userDefaultPaymentMethod = $userPrefs->default_payment_method;
			}
			return $this->userDefaultPaymentMethod;
		} 
		
		/**
		 * Gets the Openy default method for payments
		 * @return Integer|NULL If any, returns the openy default method configured for payments
		 * @see \Openy\Traits\Properties\DefaultPaymentMethodTrait Default Payment Method Trait 
		 */
		private function getOpenyOptionsDefaultPaymentMethod(){
			if (is_null($this->openyOptionsDefaultPaymentMethod)){		
				$openyOptions = NULL;
			
				if ($this instanceof OptionsInterface)
					$openyOptions = $this->getOptions();
				
				if ($openyOptions && ($openyOptions instanceof \Openy\Options\OpenyOptions)){
					$openyDefaultMethodName = $openyOptions->getDefaultPaymentMethod();
					$methods = $openyOptions->getPaymentMethods();
					$this->openyOptionsDefaultPaymentMethod = $methods[$openyDefaultMethodName];
				}
			}				
			return $this->openyOptionsDefaultPaymentMethod;
		}
		
		/**
		 * Gets the Openy available payment methods
		 * @return array An array containing the configured payment methods available
		 * @see \Openy\Traits\Properties\DefaultPaymentMethodTrait Default Payment Method Trait 
		 */
		private function getOpenyPaymentMethods(){
			$openyOptions = NULL;
			$paymentMethods = [];
			
			if ($this instanceof OptionsInterface)
				$openyOptions = $this->getOptions();
				
			if ($openyOptions && ($openyOptions instanceof \Openy\Options\OpenyOptions))
				$paymentMethods = $openyOptions->getPaymentMethods();
			
			return (array)$paymentMethods;				
		}

	/**
	 * Returns the user preference value for default payment method
	 * @return string The identifier for user preferences selected credit card or any other payment method configured
	 * @see \Openy\Traits\Properties\DefaultPaymentMethodTrait Default Payment Method Trait 
	 */	
	public function getDefaultPaymentMethodId(){
		if (is_null($this->userDefaultPaymentMethodId)){
			$userPrefs = NULL;		
			$userDefaultMethodId = NULL;
			$userDefaultCreditCardId = NULL;
				
			if ($this instanceof PreferenceAwareInterface)
				$userPrefs = $this->getUserPrefs();
			if ($userPrefs){
				if (property_exists($userPrefs, "default_payment_method_id"))		
					$userDefaultMethodId = $userPrefs->default_payment_method_id;
				if (property_exists($userPrefs, "default_credit_card"))
					$userDefaultCreditCardId = $userPrefs->default_credit_card;
			}
			$this->userDefaultPaymentMethodId = ($userDefaultMethodId ? : $userDefaultCreditCardId); 
		}
	    return $this->userDefaultPaymentMethodId;
	}

}