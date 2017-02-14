<?php
namespace Opypos\V1\Rest\Pump;

use Zend\Paginator\Paginator;

class PumpCollection extends Paginator
{
	public function toJson()
	{
    	$this->setItemCountPerPage($itemCounterPerPage = -1);
    	return parent::toJson();
	}

}
