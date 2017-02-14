<?php

namespace Openy\Model\Station;

use Openy\Model\Station\OpyStationEntity;
use Openy\Traits\Classes\BillingDataTrait;
use Openy\Interfaces\Classes\BillingDataInterface;

class BillingDataOpyStationEntity
	extends OpyStationEntity
	implements BillingDataInterface
{
	/**
	 * Billing Data Trait provides properties
	 * $billingName, $billingAddress, $billingId, $billingWeb, $billingLogo, $billingMail, $billingPhone;
	 *
	 */
	use BillingDataTrait;


}