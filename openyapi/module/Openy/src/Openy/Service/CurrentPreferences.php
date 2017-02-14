<?php
namespace Openy\Service;

class CurrentPreferences 
{
    protected $currentUser;
    protected $repository;
    
    public function __construct($currentUser, $repository)
    {
        $this->currentUser = $currentUser;
        $this->repository = $repository;
    }
        
    public function getPreference($property = null)
    {
        $pref = $this->repository->fetch($this->currentUser->getUser('iduser'));
        if($property)
            return $pref->$property;
        
        return $pref;      
    }
    
    public function setPreference($property, $value)
    {
    	$data = new \StdClass;
    	$data->{$property} = $value;
    	$this->repository->patch($this->currentUser->getUser('iduser'),$data);
    	return $this;
    }
  
}
