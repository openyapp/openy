<?php
namespace Opypos\V1\Rest\Closest;

use Zend\Paginator\Paginator;

class ClosestCollection extends Paginator
{
    public function toJson()
    {
        $this->setItemCountPerPage($itemCounterPerPage = -1);
        return parent::toJson();
    }
}
