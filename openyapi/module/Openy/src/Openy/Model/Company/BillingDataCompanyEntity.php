<?php

namespace Openy\Model\Company;

use Openy\Model\Company\CompanyEntity;
use Openy\Traits\Classes\BillingDataTrait;
use Openy\Interfaces\Classes\BillingDataInterface;

class BillingDataCompanyEntity
	extends CompanyEntity
	implements BillingDataInterface
{
	/**
	 * Billing Data Trait provides properties
	 * $billingName, $billingAddress, $billingId, $billingWeb, $billingLogo, $billingMail, $billingPhone;
	 *
	 */
	use BillingDataTrait;


}