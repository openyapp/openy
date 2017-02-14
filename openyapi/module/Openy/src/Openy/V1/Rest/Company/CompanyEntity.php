<?php
namespace Openy\V1\Rest\Company;

use Openy\Traits\Classes\BillingDataTrait;

class CompanyEntity
{
	/**
	 * Brings following properties:
	 * 	public $billingName;
	 * 	public $billingAddress;
	 * 	public $billingId;
	 *  public $billingWeb;
	 *	public $billingLogo;
	 *	public $billingMail;
	 *	public $billingPhone;
	 */
	use BillingDataTrait;

	public $idcompany;
	public $merchantcode;
	public $terminal;
	public $secret;
	public $description;

}
