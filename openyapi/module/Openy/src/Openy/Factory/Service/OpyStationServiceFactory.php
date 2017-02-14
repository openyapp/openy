<?php
/**
 * Factory.
 * Openy Station Service Factory
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Stations\Openy
 * @category Stations
 * @see Openy\Module
 *
 */
namespace Openy\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Openy\Service\OpyStationService;

/**
 * OpyStationServiceFactory.
 * Factorizes Openy Station Service instance(s)
 *
 * @uses Openy\Service\OpyStationService Openy Station Service
 * @uses Openy\Interfaces\Service\OpyStationServiceInterface Openy Station Service Interface
 *
 * @uses Zend\ServiceManager\FactoryInterface Zend Factory Interface
 * @uses Zend\ServiceManager\ServiceLocatorInterface Zend ServiceLocator Interface
 */
class OpyStationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
    	$companyService= $sl->get('Openy\Service\Company');
    	$mapper 		= $sl->get('Openy\Mapper\OpyStation');
    	$options        = $sl->get('Openy\Service\OpenyOptions');
        $service 		= new OpyStationService($companyService, $mapper, $options);
        return $service;
    }
}