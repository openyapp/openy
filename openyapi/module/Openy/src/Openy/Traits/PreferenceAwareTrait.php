<?php
namespace Openy\Traits;

use Openy\Service\CurrentPreferences;

use Zend\ServiceManager\ServiceLocatorAwareTrait;


trait PreferenceAwareTrait 
{
	protected $preference;
	
    use ServiceLocatorAwareTrait;
	
    public function setPreference(CurrentPreferences $preference)
    {
    	$this->preference = $preference;
    }
    
    public function getPreference()
    {
    	if (!$this->preference instanceof CurrentPreferences) 
            $this->setPreference($this->getServiceLocator()->get('Openy\Service\CurrentPreferences'));

    	return $this->preference;
    }

    public function getUserPrefs($property = null)
    {
    	return $this->getPreference()->getPreference($property);
    }
}