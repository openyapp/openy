<?php
/**
 * Preferences Interface.
 * Defines getter and setter for a property of an CurrentPreferences kind
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Preferences\Interfaces
 * @see Openy\Service\CurrentPreferences
 *
 */
namespace Openy\Interfaces\Properties;

use Openy\Service\CurrentPreferences;

/**
 * Preferences Property Interface.
 * Defines getter and setter for a property of an OpenyOptions kind
 *
 * @uses Openy\Service\CurrentPreferences Current Preferences Service 
 */
interface PreferencesInterface
{
	public function setPreferences(CurrentPreferences $preferences);	
	public function getPreferences();
}