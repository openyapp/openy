<?php

namespace Openy\Interfaces;
use Openy\Interfaces\Classes\BillingDataInterface;

interface BillingDataMapperInterface
{
	/**
	 * Extracts billing data from entity and compounds a BillingDataEntity
	 * @param  BillingDataInterface $entity The entity from where to take billing data info
	 * @return \Openy\Model\Classes\BillingDataEntity Billing data compounded entity
	 */
	public function getBillingData(BillingDataInterface $entity);

}