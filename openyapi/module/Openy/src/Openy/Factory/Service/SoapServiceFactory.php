<?php
/**
 * Factory.
 * Openy SOAP Client Service Factory
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment\POS\SOAP
 * @category SOAP
 * @see Openy\Module
 *
 */
namespace Openy\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Openy\Service\SoapService;

/**
 * SoapServiceFactory.
 * Factorizes POS SOAP Client Service instance(s)
 *
 * @uses Openy\Service\SoapService POS SOAP Client Service
 * @see \Openy\Interfaces\Service\SoapServiceInterface POS SOAP Client Service Interface
 * @uses Zend\ServiceManager\FactoryInterface Zend Factory Interface
 * @uses Zend\ServiceManager\ServiceLocatorInterface Zend ServiceLocator Interface
 */
class SoapServiceFactory implements FactoryInterface
{
	/**
	 * (non-PHPdoc)
	 * @see \Zend\ServiceManager\FactoryInterface::createService()
	 * @return \Openy\Interfaces\Service\SoapServiceInterface 
	 */
    public function createService(ServiceLocatorInterface $sl)
    {
		$requestMapper		= $sl->get('Openy\Mapper\TPV\Request');
		$responseMapper		= $sl->get('Openy\Mapper\TPV\Response');
		$soapMapper			= $sl->get('Openy\Mapper\TPV\SOAP');
		$options           	= $sl->get('Openy\Service\OpenyOptions');
        $tpvoptions         = $sl->get('Openy\Service\TpvOptions');

    	return new SoapService($requestMapper,$responseMapper,$soapMapper,$options,$tpvoptions);
    }
}