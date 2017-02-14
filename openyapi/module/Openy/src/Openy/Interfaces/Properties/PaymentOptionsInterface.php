<?php
/**
 * PaymentOptions property Interface.
 * 
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment
 * @category Configuration
 * @see Openy\Interfaces\Properties\Options
 * @see Openy\Service\AbstractService
 *
 */
namespace Openy\Interfaces\Properties;

use Openy\Options\PaymentOptions;

/**
 * PaymentOptionsInterface.
 * Defines getter and setter for a property of a PaymentOptions kind
 *
 * @uses Openy\Options\PaymentOptions PaymentOptions class
 * @see  \Openy\Service\PaymentService Payment Service
 * @see \Openy\Traits\Properties\PaymentOptionsTrait Payment Options Trait
 * @see \Zend\ServiceManger\ServiceLocatorAwareInterface Zend ServiceLocator Aware Interface
 */

interface PaymentOptionsInterface
{

    /**
     * Sets a property with options focused on Payments an their policies
     * @param PaymentOptions Options about payment (e.g. available taxes)
     * @return OptionsInterface
     */
    public function setPaymentOptions(PaymentOptions $options);

    /**
     * Gets a property storing options focused on Payments an their policies
     * @return PaymentOptions Options about payment (e.g. available taxes)
     */
    public function getPaymentOptions();
}