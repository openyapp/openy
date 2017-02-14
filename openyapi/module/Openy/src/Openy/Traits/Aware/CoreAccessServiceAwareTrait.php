<?php
namespace Openy\Traits\Aware;

use Openy\Interfaces\Service\Core\AccessInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

trait CoreAccessServiceAwareTrait
{
    protected $coreAccessService;

    public function getCoreAccessService(){
        if     (($this instanceof ServiceLocatorAwareInterface)
            || (property_exists($this, "serviceLocator")
                && $this->serviceLocator instanceof ServiceLocatorInterface)
                )
        {
            $this->coreAccessService = $this->coreAccessService
                                        ?
                                        : $this->getServiceLocator()->get('Openy\Service\Core\Access');
        }
        return $this->coreAccessService;
    }

    public function setCoreAccessService(AccessInterface $coreAccessService){
        $this->coreAccessService = $coreAccessService;
    }

}