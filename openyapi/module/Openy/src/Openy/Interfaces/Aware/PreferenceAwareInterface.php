<?php
/**
 * Preference Aware Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Preferences\Interfaces
 * @see Zend\ServiceManager\ServiceLocatorAwareInterface
 *
 */
namespace Openy\Interfaces\Aware;

use Openy\Interfaces\Properties\PreferencesInterface;
use Openy\Interfaces\Properties\UserPreferencesInterface;

/**
 * 
 * Preference Aware Interface.
 * Defines getter and setter for Preferences Service and session persistent User Preferences, 
 *
 * @uses \Openy\Interfaces\PreferencesInterface Preference Interface
 * @uses \Openy\V1\Rest\Preference\PreferenceEntity Preference Entity
 *
 */
interface PreferenceAwareInterface
	extends PreferencesInterface	        
{	
}