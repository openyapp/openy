<?php
/**
 * Options trait.
 * Provides getter and setter for an property of an OpenyOptions kind
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core
 * @see Openy\Interfaces\Properties\Options
 * @see Openy\Service\AbstractService
 *
 */
namespace Openy\Traits\Properties;

use Zend\Stdlib\AbstractOptions;

/**
 * OptionsTrait.
 * Implements Interfaces\Properties\OptionsInterface
 * 
 * @uses Zend\StdLib\AbstractOptions Zend Abstract Options class
 * @see  Interfaces\Properties\OptionsInterface OptionsInterface
 * 
 */
trait OptionsTrait
{
	/**
	 * (Openy) Options for a class or service
	 * @var AbstractOptions
	 */
    protected $options;

    /**
     * Sets the (Openy) options for the current instance
     * @param AbstractOptions $options
     * @return \StdClass Mapper or Service instance
     */
    public function setOptions(AbstractOptions $options)
    {
        $this->options = $options;
        return $this;
    }


    /**
     * Gets instance stored (Openy) options 
     * @return AbstractOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

}