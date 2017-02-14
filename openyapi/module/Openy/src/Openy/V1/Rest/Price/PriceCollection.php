<?php
namespace Openy\V1\Rest\Price;

use Zend\Paginator\Paginator;

class PriceCollection extends Paginator
{
    protected static $defaultItemCountPerPage = 10;
    
    /**
     * @return the $defaultItemCountPerPage
     */
    public static function getDefaultItemCountPerPage()
    {
        return PriceCollection::$defaultItemCountPerPage;
    }
    
    /**
     * @param number $defaultItemCountPerPage
     */
    public static function setDefaultItemCountPerPage($defaultItemCountPerPage)
    {
        PriceCollection::$defaultItemCountPerPage = $defaultItemCountPerPage;
    } 
}
