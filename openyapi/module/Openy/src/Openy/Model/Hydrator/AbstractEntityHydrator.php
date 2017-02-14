<?php
/**
 * Abstract Entity Hydrator.
 * Hydrator root class for Entity Hydrators
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core\Hydration
 * @category Classes
 *
 */

namespace Openy\Model\Hydrator;

use Zend\Stdlib\Hydrator\Reflection;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Openy\Interfaces\Aware\OptionsServiceAwareInterface;
use Openy\Traits\Aware\OptionsServiceAwareTrait;

/**
 * AbstractEntityHydrator.
 * 
 * Base Hydrator class for Abstract Entities descendants Hydrators.
 * Identifies family of Hydrators indented for entities descending the AbstractEntity class
 *
 * @uses http://framework.zend.com/apidoc/2.4/classes/Zend.Stdlib.Hydrator.Reflection.html Zend Reflection Hydrator (parent)
 * @see http://framework.zend.com/apidoc/2.4/classes/Zend.Stdlib.Hydrator.HydratorInterface.html Interface for Zend Hydrators
 * @uses  Openy\AbstractEntity AbstractEntity base class
 * @uses Openy\Interfaces\Aware\OptionsServiceAwareInterface Options Service AwareInterface
 * @uses Openy\Traits\Aware\OptionsServiceAwareTrait Options Service AwareTrait
 */
abstract class AbstractEntityHydrator extends Reflection
    implements HydratorInterface,
    		   OptionsServiceAwareInterface
{
	use OptionsServiceAwareTrait;
	
    /**
     * Extract values from an object
     *
     * @param  \Openy\Model\AbstractEntity $object
     * @return array
     */
    public function extract($object){
        return parent::extract($object);
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Openy\Model\AbstractEntity $object
     * @return \Openy\Model\AbstractEntity
     */
    public function hydrate(array $data, $object){
        return parent::hydrate($data,$object);
    }

}