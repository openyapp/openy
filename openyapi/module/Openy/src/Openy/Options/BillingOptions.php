<?php
/**
 * Billing Options.
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Config\Billing
 * @see Openy\Module
 *
 */
namespace Openy\Options;

use Openy\Options\SubOptions;

/**
 * Billing Options.
 *
 * Contains methods to acquire configuration values for Orders and Invoicing processes.
 * Available as service called 'Openy\Service\BillingOptions'.
 *
 * <h3>Magic Properties Example:</h3>
 * <code>
 * $defaultCompanyId = $billingOptions->invoices->company;
 * // returns 1
 * $policies = $billingOptions->policies;
 * // returns an array of constants (e.g. [POLICY_BILLING_LOCAL_DATA, POLICY_BILLING_DB_DATA_IF_AVAILABLE, POLICY_BILLING_ALLWAYS_DB_DATA])
 * $defaultInvoicesPolicy = $billingOptions->invoices->policy;
 * // returns POLICY_BILLING_DB_DATA_IF_AVAILABLE
 * $companies = $billingOptions->companies;
 * $firstCompany = $companies[1];
 *
 * </code>
 *
 * <h3>Magic Getters Example:</h3>
 * <code>
 * $defaultCompanyId = $billingOptions->getInvoices('company');
 * // returns 1
 * $defaultInvoicesPolicy = $billingOptions->getInvoices('policy');
 * // returns POLICY_BILLING_DB_DATA_IF_AVAILABLE
 *
 * </code>
 *
 * @see /phpdoc/packages/Openy.Invoicing.html Invoicing package
 * @see /phpdoc/packages/Openy.Orders.html Orders package
 * @see  Openy\Traits\Openy\Billing\DefaultCompanyBillingDataTrait DefaultCompany BillingData Trait
 *
 * @uses Openy\Model\Options\SubOptions SubOptions class
 *
 * @property-read SubOptions $companies SubOptions instance containing indexed companies local data
 *
 * @property-read Array $policies Available billing policies
 *
 * @property-read SubOptions $invoices SubOptions instance containig invoices Policy ($policy) and default local Company Id for invoices($company)
 *
 * @property-read SubOptions $receipts SubOptions instance containig receipts Policy ($policy) and default local Company Id for receipts ($company)
 *
 * @method  integer getInvoices(string $optionName) Returns the invoices policy constant ($optionName='policy') or invoices default issuer company id ($optionName='company')
 *
 * @method  integer getReceipts(string $optionName) Returns the receipts policy constant ($optionName='policy') or receipts default issuer company id ($optionName='company')
 */
class BillingOptions extends SubOptions
{
}