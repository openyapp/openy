<?php
/**
 * Company Service Aware Interface
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Stations\Openy
 * @category Company
 * @see Zend\ServiceManager\ServiceLocatorAwareInterface
 *
 */

namespace Openy\Interfaces\Aware;

use Openy\Interfaces\Service\CompanyServiceInterface as CompanyService;
use Zend\ServiceManager\ServiceLocatorInterface;

Interface CompanyServiceAwareInterface
{

    /**
     * SET Company Service
     * @param CompanyService $companyService Company Service Instance to store
     * @return  self Self instance
     *
     * @see  CompanyServiceAwareInterface CompanyServiceAwareInterface
     */
    public function setCompanyService(CompanyService $companyService);

    /**
     * GET Company Service.
     * Retrieves stored company service
     * @return CompanyService|null Company Service if located or NULL otherwise
     *
     * @see  CompanyServiceAwareInterface CompanyServiceAwareInterface
     */
    public function getCompanyService();
}
