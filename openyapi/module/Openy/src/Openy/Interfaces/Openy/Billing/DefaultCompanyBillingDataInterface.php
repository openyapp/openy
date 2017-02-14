<?php
/**
 * Default Company Billing Data Interface.
 * Defines getter methods to retrieve billing data from configuration
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Config\Billing
 * @category Configuration
 *
 */
namespace Openy\Interfaces\Openy\Billing;
 
use Openy\Interfaces\Properties\BillingOptionsInterface;

/**
 * DefaultCompanyBillingDataInterface.
 * Defines getter methods to retrieve billing data from configuration.
 * Because the need to access configuration billing options, 
 * BillingOptionsInterface implementation is required throughout its extension 
 *
 * @uses Openy\Model\Classes\BillingDataEntity Billing Data Entity
 * @uses Openy\Interfaces\Properties\BillingOptionsInterface BillingOptions Property Interface
 * @see Openy\Traits\Openy\Billing\DefaultCompanyBillingDataTrait Trait implementing this instance
 */
interface DefaultCompanyBillingDataInterface
	extends BillingOptionsInterface
{
	/**
     * Gets Local Data for Default company.
     * Allows retrieving billing data entity filled with the billing options company data stablished as default company for issuing invoices or receipts (depending on each case)
     * @param  string $case One of both, 'invoices' or 'receipts', specifying the selected default company
     * @return \Openy\Model\Classes\BillingDataEntity       A "Billing Data Entity" filled with default company data taken from local config
     * @internal {@see Openy\Traits\BillingData\LocalDataCompanyBillingDataTrait}
     */
    public function getDefaultCompanyBillingData($case = 'invoices');
	
	/**
	 * Returns the user preference value for default payment method
	 * @return string The identifier for user preferences selected credit card or any other payment method configured
	 */
    public function getDefaultCompanyId($case = 'invoices');
    
}