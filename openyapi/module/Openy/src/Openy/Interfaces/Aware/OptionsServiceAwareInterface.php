<?php
/**
 * Options Aware Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core
 * @see Zend\ServiceManager\ServiceLocatorAwareInterface
 *
 */
namespace Openy\Interfaces\Aware;

use Openy\Interfaces\Properties\OptionsInterface;

/**
 * 
 * OptionsAwareInterface.
 * Defines getter and setter for a property of an OpenyOptions kind *
 * @uses \Openy\Interfaces\Properties\OptionsInterface Options Property Interface
 * @uses Zend\Stdlib\AbstractOptions 
 *
 */
interface OptionsServiceAwareInterface
	extends OptionsInterface	        
{	
}