<?php
namespace Oauthreg\Rbac;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;

class RestGuardFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    /**
     * @var array
     */
    protected $options = [];
    
    /**
     * {@inheritDoc}
    */
    public function setCreationOptions(array $options)
    {
        $this->options = $options;
    }
    
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();
    
        /* @var \ZfcRbac\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $parentLocator->get('ZfcRbac\Options\ModuleOptions');
            
        /* @var \ZfcRbac\Service\AuthorizationService $authorizationService */
        $authorizationService = $parentLocator->get('ZfcRbac\Service\AuthorizationService');
        
        /* @var \ZF\MvcAuth\Authorization\DefaultResourceResolverListener $resourceResolver */
        $resourceResolver = $parentLocator->get('ZF\MvcAuth\Authorization\DefaultResourceResolverListener');
        
        $restGuard = new RestGuard($authorizationService, $this->options);
        
        $restGuard->setProtectionPolicy($moduleOptions->getProtectionPolicy());
        $restGuard->setResourceResolver($resourceResolver);

        return $restGuard;
    }
}