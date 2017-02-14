<?php
/**
 * UserPreferences Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Preferences\Interfaces
 * @see Zend\ServiceManager\ServiceLocatorAwareInterface
 *
 */
namespace Openy\Interfaces\Properties;

use Openy\V1\Rest\Preference\PreferenceEntity;

/**
 * 
 * UserPreferencesInterface.
 * Defines getter and setter for session persistent User Preferences, 
 * 
 * @uses \Openy\V1\Rest\Preference\PreferenceEntity Preference Entity

 */
interface UserPreferencesInterface
{
	/**
	 * Gets user set of preferences or just a single one
	 * @param string $property Single preference to get, or all of them in case of NULL 
	 * @return mixed PreferenceEntity or a mixed value when requested a single preference
	 */
    public function getUserPrefs($property = null);
    
    /**
     * Sets the user preferences to be used by instance during current session
     * @param PreferenceEntity $userPrefs
     * @return PreferenceAwareInterface Instance 
     */
    public function setUserPrefs(PreferenceEntity $userPrefs = null);
}