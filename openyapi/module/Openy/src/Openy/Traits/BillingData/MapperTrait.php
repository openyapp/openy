<?php

namespace Openy\Traits\BillingData;

use Openy\Interfaces\Classes\BillingDataInterface;
use Openy\Model\Classes\BillingDataEntity;

trait MapperTrait
{
	public function getBillingData(BillingDataInterface $entity){
		return new BillingDataEntity($entity);
	}
}