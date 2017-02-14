<?php
/**
 * Current User Preferences
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Preferences\Interfaces 
 *
 */

namespace Openy\Traits\Properties;

use Openy\V1\Rest\Preference\PreferenceEntity;
use Openy\Interfaces\Properties\PreferencesInterface;

/**
 * UserPreferencesTrait.
 * Implements UserPreferencesInterface
 *
 * @see  Openy\Interfaces\Properties\UserPreferencesInterface User Preferences Interface
 * @uses Openy\V1\Rest\Preference\PreferenceEntity Preferences Entity
 * @uses Openy\Interfaces\Properties\PreferencesInterface Preferences Interface
 */
trait UserPreferencesTrait 
{
	protected $userPrefs;	

    /**
     * Gets user set of preferences or just a single one
     * @param string $property Single preference to get, or all of them in case of NULL
     * @return mixed PreferenceEntity or a mixed value when requested a single preference
     */
    public function getUserPrefs($property = null)
    {
    	if (is_null($this->userPrefs)){
    		if ($this instanceof PreferencesInterface)
    		$this->setUserPrefs($this->getPreferences()->getPreference());
    	}
    	
    	if (($this->userPrefs) && ($property))
    		return $this->userPrefs->$property;
    	else
    		return $this->userPrefs;
    }
    
    /**
     * Sets the user preferences to be used by instance during current session
     * @param PreferenceEntity $userPrefs
     * @return Openy\Interfaces\Aware\PreferenceAwareInterface Instance
     */
    public function setUserPrefs(PreferenceEntity $userPrefs = null){
    	$this->userPrefs = $userPrefs;
    	return $this;
    }
}