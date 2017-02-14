<?php
/**
 * Company Service Aware
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Stations\Openy
 * @category Company
 * @see Zend\ServiceManager\ServiceLocatorAwareTrait
 *
 */
namespace Openy\Traits\Aware;

use Openy\Interfaces\Service\CompanyServiceInterface as CompanyService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
/**
 * Company Service Aware Trait.
 * Implements CompanyServiceAwareInterface
 *
 * @see  CompanyServiceAwareInterface CompanyServiceAwareInterface
 * @uses Openy\Service\Company Company Service
 * @see Zend\ServiceManager\ServiceLocatorAwareInterface ServiceLocatorAwareInterface
 */
trait CompanyServiceAwareTrait
{

    /**
     * Company Service Instance
     * @var Openy\Service\Company
     * @ignore
     */
    protected $companyService;

    /**
     * SET Company Service
     * @param CompanyService $companyService Company Service Instance to store
     * @return  self Self instance
     *
     * @see  CompanyServiceAwareTrait Provided by CompanyServiceAwareTrait
     */
    public function setCompanyService(CompanyService $companyService){
        $this->companyService = $companyService;
        return $this;
    }

    /**
     * GET Company Service.
     * Retrieves company service from ServiceLocator if {@link http://framework.zend.com/apidoc/2.4/classes/Zend.ServiceManager.ServiceLocatorAwareInterface.html ServiceLocatorAwareInterface} is implemented
     * @return CompanyService|null Company Service if located or NULL otherwise
     *
     * @see  CompanyServiceAwareTrait Provided by CompanyServiceAwareTrait
     */
    public function getCompanyService(){
        if     (($this instanceof ServiceLocatorAwareInterface)
            || (property_exists($this, "serviceLocator")
                && $this->serviceLocator instanceof ServiceLocatorInterface)
                )
        {
            $this->companyService = $this->companyService ? : $this->getServiceLocator()->get('Openy\Service\Company');
        }
        return $this->companyService;

    }

}