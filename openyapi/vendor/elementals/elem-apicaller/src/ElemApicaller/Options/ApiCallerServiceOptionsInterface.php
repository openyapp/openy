<?php

namespace ElemApicaller\Options;

interface ApiCallerServiceOptionsInterface
{
    /**
     * set url
     *
     * @param bool $useRedirectParameterIfPresent
     */
    public function setUrl($url);

    /**
     * get url
     *
     * @return bool
    */
    public function getUrl();
    
    /**
     * set resource
     *
     * @param bool $useRedirectParameterIfPresent
     */
    public function setResources($resources);
    
    /**
     * get resources
     *
     * @return bool
    */
    public function getResources();
    
    /**
     * get resource by name
     *
     * @return bool
     */
    public function getResource($name);
}