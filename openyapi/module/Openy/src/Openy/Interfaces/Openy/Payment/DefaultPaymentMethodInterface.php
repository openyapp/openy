<?php
/**
 * DefaultPaymentMethod.
 * Defines getters for default method and id to be used in payments
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Preferences\Interfaces\Payment
 *
 */
namespace Openy\Interfaces\Openy\Payment;

/**
 * Default Payment Method Property.
 * Defines getters for default method and id to be used in payments
 *
 */
interface DefaultPaymentMethodInterface
{
	/**
	 * Returns the value (integer) for default payment method
	 * @param  boolean $getKey If true, forces to return the name of the payment method instead of its value
	 * @return int|string
	 */
	public function getDefaultPaymentMethod($getKey = false);
	
	/**
	 * Returns the user preference value for default payment method
	 * @return string The identifier for user preferences selected credit card or any other payment method configured
	 */
	public function getDefaultPaymentMethodId();
}