<?php
/**
 * Billing Options trait.
 * Provides getter and setter for an property of an BillingOptions kind
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Config\Billing
 * @category Configuration
 * @see Openy\Module
 * @see Openy\Interfaces\Properties\Options
 */
namespace Openy\Traits\Properties;

use Openy\Options\BillingOptions;

/**
 * BillingOptionsTrait.
 * Implements Interfaces\Properties\BillingOptionsInterface
 * 
 * @uses Openy\Options\BillingOptions Openy Billing Options class
 * @see  Openy\Interfaces\Properties\BillingOptionsInterface BillingOptionsInterface
 * 
 */
trait BillingOptionsTrait
{
	
	/**
	 * Openy Billing Options for a class or service
	 * @var BillingOptions
	 * @see \Openy\Traits\Properties\BillingOptionsTrait
	 */
    protected $billingoptions;

    /**
     * Sets the Openy Billing options for the current instance
     * @param BillingOptions $options
     * @return \Openy\Interfaces\Properties\BillingOptionsInterface Instance
	 * @see \Openy\Traits\Properties\BillingOptionsTrait 
     */
    public function setBillingOptions(BillingOptions $options)
    {
        $this->billingoptions = $options;
        return $this;
    }

    /**
     * Gets instance stored Billing options 
     * @return BillingOptions
     * @see \Openy\Traits\Properties\BillingOptionsTrait
     */
    public function getBillingOptions()
    {
        return $this->billingoptions;
    }

}