<?php

namespace ElemApicaller\Options;

class ModuleOptions implements ApiCallerServiceOptionsInterface
{
    protected $url;
    
    /**
     * @param array (collection, entity, array header)
     * @example ('oauth' => array('oauth', '/%s', array('Accept' => 'application/vnd.module.v1+json'))
     */
    protected $resources;
     
	/**
     * @return the $url
     */
    public function getUrl()
    {
        return $this->url;
    }

	/**
     * @return the $resources
     */
    public function getResources()
    {
        return $this->resources;
    }

	/**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

	/**
     * @param multitype:multitype:string multitype:string    $resources
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
        return $this;
    }
    
    /**
     * @return the $resource[$name]
     */
    public function getResource($name)
    {
        return $this->resources[$name];
    }
    
}
