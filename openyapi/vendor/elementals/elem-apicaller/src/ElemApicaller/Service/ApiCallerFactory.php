<?php

namespace ElemApicaller\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApiCallerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceManager)
	{
	    $options = $serviceManager->get('apicaller_module_options');
 		$service = new ApiCaller($options, $serviceManager);	
 		return $service;
	} 
}
