<?php
/**
 * TPV Options trait.
 * Provides getter and setter for an property of an OpenyOptions kind
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment\POS
 * @category Configuration
 *
 */
namespace Openy\Traits\Properties;

use Openy\Options\TpvOptions;

/**
 * TpvOptionsTrait.
 * Implements Interfaces\Properties\TpvOptionsInterface
 * 
 * @uses Openy\Options\TpvOptions Openy POS (TPV) Options class
 * @see  Openy\Interfaces\Properties\TpvOptionsInterface OptionsInterface
 * 
 */
trait TpvOptionsTrait
{
	
	/**
	 * POS (TPV) options for a class or service
	 * @var TpvOptions
	 */
    protected $tpvoptions;

    /**
     * Sets the POS (TPV) options for the current instance
     * @param TpvOptions $options
     * @return \Openy\Interfaces\Properties\TpvOptionsInterface Instance
     */
    public function setTpvOptions(TpvOptions $options)
    {
        $this->tpvoptions = $options;
        return $this;
    }

    /**
     * Gets instance stored POS (TPV) options 
     * @return TpvOptions
     */
    public function getTpvOptions()
    {
        return $this->tpvoptions;
    }

}