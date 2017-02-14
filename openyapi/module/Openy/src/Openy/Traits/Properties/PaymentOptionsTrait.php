<?php
/**
 * Payment Options trait.
 * Provides getter and setter for an property of an PaymentOptions kind
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment
 * @category Configuration
 * @see Openy\Module
 * 
 */
namespace Openy\Traits\Properties;

use Openy\Options\PaymentOptions;

/**
 * PaymentOptionsTrait.
 * Implements Interfaces\Properties\PaymentOptionsInterface
 *
 * @uses Openy\Options\PaymentOptions Openy Payment Options class
 * @see  Openy\Interfaces\Properties\PaymentOptionsInterface PaymentOptionsInterface
 *
 */
trait PaymentOptionsTrait
{

    /**
     * Payment options
     * @var PaymentOptions
     */
    protected $paymentOptions;

    /**
     * Gets PaymentOptions property
     * @return PaymentOptions
     */
    public function getPaymentOptions(){        
        return $this->paymentOptions;
    }

    /**
     * Sets paymentOptions property
     * @return PaymentOptions
     */
    public function setPaymentOptions(PaymentOptions $paymentOptions){
        $this->paymentOptions = $paymentOptions;
        return $this;
    }

}