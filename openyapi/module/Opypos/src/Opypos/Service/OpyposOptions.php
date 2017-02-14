<?php

namespace Opypos\Service;

use Zend\Stdlib\AbstractOptions;

class OpyposOptions extends AbstractOptions 
{
    protected $posNetwork = null;
    protected $maxDistace = 50; //meters
    protected $raisePumpTimeout;
    protected $finishRefuel;
    protected $androidPush;
    
    
    
    
	/**
     * @return the $androidPush
     */
    public function getAndroidPush()
    {
        return $this->androidPush;
    }

	/**
     * @param field_type $androidPush
     */
    public function setAndroidPush($androidPush)
    {
        $this->androidPush = $androidPush;
        return $this;
    }

	/**
     * @return the $raisePumpTimeout
     */
    public function getRaisePumpTimeout()
    {
        return $this->raisePumpTimeout;
    }

	/**
     * @return the $finishRefuel
     */
    public function getFinishRefuel()
    {
        return $this->finishRefuel;
    }

	/**
     * @param field_type $raisePumpTimeout
     */
    public function setRaisePumpTimeout($raisePumpTimeout)
    {
        $this->raisePumpTimeout = $raisePumpTimeout;
        return $this;
    }

	/**
     * @param field_type $finishRefuel
     */
    public function setFinishRefuel($finishRefuel)
    {
        $this->finishRefuel = $finishRefuel;
        return $this;
    }

	/**
     * @return the $posNetwork
     */
    public function getPosNetwork()
    {
        return $this->posNetwork;
    }

	/**
     * @param field_type $posNetwork
     */
    public function setPosNetwork($posNetwork)
    {
        $this->posNetwork = $posNetwork;
        return $this;
    }
    
	/**
     * @return the $maxDistace
     */
    public function getMaxDistace()
    {
        return $this->maxDistace;
    }

	/**
     * @param number $maxDistace
     */
    public function setMaxDistace($maxDistace)
    {
        $this->maxDistace = $maxDistace;
        return $this;
    }


           
    
    
    
}
