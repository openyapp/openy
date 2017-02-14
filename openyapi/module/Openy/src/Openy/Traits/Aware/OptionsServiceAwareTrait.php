<?php
/**
 * Options Service Aware Trait.
 * Provides getter and setter for an property of an OpenyOptions kind
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core
 * @see Openy\Interfaces\Properties\Options
 * @see Openy\Service\AbstractService
 *
 */
namespace Openy\Traits\Aware;

use Openy\Traits\Properties\OptionsTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * OptionsServiceAwareTrait.
 * Implements OptionsServiceAwareInterface
 *
 * @see  Openy\Interfaces\Aware\OptionServiceAwareInterface (Openy) Options Service Aware Interface
 * @uses Openy\Traits\Properties\OptionsTrait Options Property Trait
 * @uses Zend\ServiceManager\ServiceLocatorAwareInterface Zend ServiceLocator AwareInterface
 * @uses Zend\ServiceManager\ServiceLocatorInterface Zend ServiceLocator Interface
 */
trait OptionsServiceAwareTrait
{
	use OptionsTrait{
        getOptions as parent_getOptions;
    }

    public function getOptions(){
        if     (($this instanceof ServiceLocatorAwareInterface)
            || (property_exists($this, "serviceLocator")
                && $this->serviceLocator instanceof ServiceLocatorInterface)
                )
        {
            if (is_null($this->parent_getOptions()))
            $this->setOptions($this->getServiceLocator()->get('Openy\Service\OpenyOptions'));
        }
        return $this->parent_getOptions();
    }

}