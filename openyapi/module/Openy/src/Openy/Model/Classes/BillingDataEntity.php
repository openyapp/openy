<?php
/**
 * BillingDataEntity.
 * Entity with billing attributes
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Classes\Billing
 *
 */
namespace Openy\Model\Classes;

use Openy\Traits\Classes\BillingDataTrait;
use Openy\Interfaces\Classes\BillingDataInterface;

/**
 * Entity for Billing Data.
 * Sets an structure for collecting, parsing and validating billing information.
 *
 * @see /phpdoc/packages/Openy.Invoicing.html Invoicing package
 * @see /phpdoc/packages/Openy.Orders.html Orders package
 * @uses  Openy\Interfaces\BillingDataInterface Interface for Billing Data Entities (implemented)
 * @uses  Openy\Traits\Classes\BillingDataTrait Trait implementing Billing Data Interface
 *
 */
class BillingDataEntity
	implements BillingDataInterface
{
    /**
     * {@inheritDoc}
     */
	use BillingDataTrait;
}