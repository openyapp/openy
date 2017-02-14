<?php
/**
 * Preferences By User Read Access Interface.
 * Defines getter for accessing preferences stored for any registered user
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Preferences\Interfaces
 * @see Openy\V1\Rest\Preference\PreferenceEntity
 *
 */
namespace Openy\Interfaces\Openy\Preferences;

/**
 * PreferencesByUserInterface.
 * Defines getter for accessing preferences stored for any registered user
 *
 * @uses Openy\V1\Rest\Preference\PreferenceEntity Preferences entity 
 */
interface PreferencesByUserInterface
{	
	/**
	 * Gets preferences for a given user
	 * @param integer $iduser User identifier
	 * @return \Openy\V1\Rest\Preference\PreferenceEntity
	 */
	public function getPreferences($iduser);
}