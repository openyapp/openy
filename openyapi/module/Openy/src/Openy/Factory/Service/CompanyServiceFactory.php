<?php
/**
 * Factory.
 * Company Service Factory
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Stations\Openy
 * @category Company
 * @see Openy\Module
 *
 */
namespace Openy\Factory\Service;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Openy\Service\CompanyService;

/**
 * CompanyServiceFactory.
 * Factorizes Creditcard Service instance(s)
 *
 * @uses Openy\Service\CompanyService Company Service class
 * @see \Openy\Interfaces\Service\CompanyServiceInterface Company Service Interface
 * @uses Zend\ServiceManager\FactoryInterface Zend Factory Interface
 * @uses Zend\ServiceManager\ServiceLocatorInterface Zend ServiceLocator Interface
 */
class CompanyServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
    	$mapper 		= $sl->get('Openy\V1\Rest\Company\CompanyMapper');
        $tpvMapper      = $sl->get('Openy\Mapper\TpvCompany');
        $stationMapper  = $sl->get('Openy\Mapper\OpyStation');
        $service 		= new CompanyService($mapper, $tpvMapper, $stationMapper);
        return $service;
    }
}