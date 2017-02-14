<?php
/**
 * Current Preferences Service Aware
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Preferences\Interfaces
 * @see Zend\ServiceManager\ServiceLocatorAwareTrait
 *
 */

namespace Openy\Traits\Aware;

use Openy\Service\CurrentPreferences;
use Openy\V1\Rest\Preference\PreferenceEntity;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

use Openy\Traits\Properties\PreferencesTrait;
/**
 * PreferenceAwareTrait.
 * Implements PreferenceAwareInterface
 *
 * @see  Openy\Interfaces\Aware\PreferenceAwareInterface Preference Aware Interface
 * @uses Openy\Traits\Properties\PreferencesTrait Imported Preferences Property Trait
 * @uses Openy\Service\CurrentPreferences Current Preferences Service
 * @uses Openy\V1\Rest\Preference\PreferenceEntity Preferences Entity
 * @see Zend\ServiceManager\ServiceLocatorAwareInterface ServiceLocatorAwareInterface
 */
trait PreferenceAwareTrait 
{
	use PreferencesTrait
	{
		getPreferences as inheritedGetPreferences;
	}
	
	/**
	 * Gets current preferences.
	 * If preferences have not been provided, are requested through Service Locator
	 * @return CurrentPreferences
	 */
    public function getPreferences()
    {
    	$preferences = $this->inheritedGetPreferences();    	    	    	
    	
    	if ($preferences instanceof CurrentPreferences)
    		return $preferences;
    	else
    	{
    		if     (   ($this instanceof ServiceLocatorAwareInterface)
    				|| (   property_exists($this, "serviceLocator")
    					&& $this->serviceLocator instanceof ServiceLocatorInterface)
    				)
    			$this->setPreferences($this->getServiceLocator()->get('Openy\Service\CurrentPreferences'));    			
    	}
    	return $this->inheritedGetPreferences();
    }    
}