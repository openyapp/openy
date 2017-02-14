<?php
namespace Opypos\V1\Rest\Price;

use Zend\Paginator\Paginator;

class PriceCollection extends Paginator
{
    public function toJson()
    {
        $this->setItemCountPerPage($itemCounterPerPage = -1);
        return parent::toJson();
    }
}
