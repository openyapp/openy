<?php
/**
 * Options Interface.
 * Defines getter and setter for a property of an OpenyOptions kind
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core
 * @see Openy\Service\AbstractService
 *
 */
namespace Openy\Interfaces\Properties;

use Zend\Stdlib\AbstractOptions;


/**
 * Options Property Interface.
 * Defines getter and setter for a property of an OpenyOptions kind
 *
 * @uses Zend\Stdlib\AbstractOptions 
 */
interface OptionsInterface
{
	/**
	 * Sets Options property
	 * @param AbstractOptions $options
	 * @return OptionsInterface
	 */
    public function setOptions(AbstractOptions $options);
    
    /**
     * Gets Options property
     * @return AbstractOptions
     */
    public function getOptions();
}