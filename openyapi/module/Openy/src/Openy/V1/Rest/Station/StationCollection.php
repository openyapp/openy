<?php
namespace Openy\V1\Rest\Station;

use Zend\Paginator\Paginator;

class StationCollection extends Paginator
{
    protected static $defaultItemCountPerPage = 10;
    
	/**
     * @return the $defaultItemCountPerPage
     */
    public static function getDefaultItemCountPerPage()
    {
        return StationCollection::$defaultItemCountPerPage;
    }

	/**
     * @param number $defaultItemCountPerPage
     */
    public static function setDefaultItemCountPerPage($defaultItemCountPerPage)
    {
        StationCollection::$defaultItemCountPerPage = $defaultItemCountPerPage;
    }

    
    
}
