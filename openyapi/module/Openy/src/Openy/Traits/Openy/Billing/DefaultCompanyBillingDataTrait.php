<?php
/**
 * Trait.
 * Company Local Billing Data Trait.
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Config\Billing
 * @category Configuration
 * @see Openy\Module
 *
 */


namespace Openy\Traits\Openy\Billing;

use Openy\Model\Classes\BillingDataEntity;
use Zend\Stdlib\AbstractOptions;

/**
 * DefaultCompanyBillingDataTrait.
 * Implements DefaultCompanyBillingDataInterface
 *
 * @see \Openy\Interfaces\Openy\Billing\DefaultCompanyBillingDataInterface DefaultCompany BillingData Interface
 * @uses Openy\Model\Classes\BillingDataEntity Billing Data Entity
 * @see Openy\Interfaces\Properties\BillingOptionsInterface BillingOptions Property Interface
 *
 *
 */
trait DefaultCompanyBillingDataTrait
{

    /**
     * Gets Local Data for Default company.
     * Allows retrieving billing data entity filled with the billing options company data stablished as default company for issuing invoices or receipts (depending on each case)
     * @param  string $case One of both, 'invoices' or 'receipts', specifying the selected default company
     * @return BillingDataEntity       A "Billing Data Entity" filled with default company data taken from local config
     * @internal {@see Openy\Traits\Openy\Billing\DefaultCompanyBillingDataTrait}
     */
    public function getDefaultCompanyBillingData($case = 'invoices'){
        $defaultCompanyId = $this->getBillingOptions()[$case]->getCompany();
        $localData = $this->getBillingOptions()->getCompanies($defaultCompanyId);
        if ($localData instanceof AbstractOptions)
            $localData = $localData->toArray();
        return new BillingDataEntity($localData);
    }

    /**
     * Gets Id for Default company
     * @param  string $case One of both, 'invoices' or 'receipts', specifying the selected default company
     * @return Integer       Id for the default company taken from config
     * @internal {@see Openy\Traits\Openy\Billing\DefaultCompanyBillingDataTrait}
     */
   public function getDefaultCompanyId($case = 'invoices'){
        return $this->getBillingOptions()[$case]->getCompany();
    }

}