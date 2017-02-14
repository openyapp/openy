<?php
/**
 * Openy Billing Options Interface.
 * Defines getter and setter for a property of a BillingOptions kind
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Config\Billing
 * @category Configuration
 * @see Openy\Interfaces\Properties\OptionsInterface
 * @see Openy\Module
 */
namespace Openy\Interfaces\Properties;

use Openy\Options\BillingOptions;


/**
 * BillingOptionsInterface.
 * Defines getter and setter for a property of a BillingOptions kind
 * 
 * @uses Openy\Options\BillingOptions Openy Billing Options class
 * @see  Openy\Traits\Properties\BillingOptionsTrait Trait for managing BillingOptions property 
 */
interface BillingOptionsInterface
{
    public function setBillingOptions(BillingOptions $options);
    public function getBillingOptions();
}