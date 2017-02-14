<?php
/**
 * Preferences Interface.
 * Provides getter and setter for a property of an CurrentPreferences kind
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Preferences\Traits
 * @see Openy\Service\CurrentPreferences
 *
 */
namespace Openy\Traits\Properties;

use Openy\Service\CurrentPreferences;

/**
 * PreferencesTrait.
 * Implements Interfaces\Properties\PreferencesInterface
 *
 * @uses Openy\Service\CurrentPreferences Current Preferences Service 
 * 
 */
trait PreferencesTrait
{
	/**
	 * (User's) Current preferences to be used during session
	 * @var CurrentPreferences
	 */
    protected $preferences;

    /**
     * Sets the Current Preferences
     * @param CurrentPreferences $preferences
     * @return \StdClass Mapper or Service instance
     */
    
    public function setPreferences(CurrentPreferences $preferences){
    	$this->preferences = $preferences;
    	return $this;
    }
    
    /**
     * Gets the stored current Preferences
     * @return CurrentPreferences 
     */
    public function getPreferences(){
    	return $this->preferences;
    }
}