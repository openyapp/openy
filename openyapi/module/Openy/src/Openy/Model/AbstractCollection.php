<?php
/**
 * Abstract Class.
 * Contains Abstract Base class for Collections
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core
 * @category Classes
 *
 */
namespace Openy\Model;

use Zend\Paginator\Paginator;
use Openy\Interfaces\CollectionInterface;

use Zend\Paginator\Adapter\AdapterInterface;
use Zend\Paginator\Adapter\ArrayAdapter;
use Openy\Model\AbstractEntity;


/**
 * AbstractCollection.
 *
 * Implements CollectionInterface.
 * Provides methods emulating Zend Paginator,
 * and provides methods for importing and exporting arrays of Abstract Entities
 *
 * @uses Openy\Interfaces\CollectionInterface
 * @uses Zend\Paginator\Paginator Zend Paginator
 * @uses Zend\Paginator\Adapter\ArrayAdapter Zend Array Adapter
 * @uses Zend\Paginator\Adapter\AdapterInterface Zend Adapter Interface
 *
 * @see \Openy\Model\AbstractEntity Abstract Entity
 * @see \Countable
 * @see \IteratorAggregate
 */
abstract class AbstractCollection
    extends Paginator
    implements CollectionInterface
{

	/**
	 * {@inheritDoc}
	 * @see Openy\Interfaces\CollectionInterface CollectionInterface 
	 */
    public function __construct($entities){
        $adapter = null;
        if ($entities instanceof AdapterInterface)
            $adapter = $entities;
        else if (is_array($entities))
            $adapter = new ArrayAdapter($entities);
        parent::__construct($adapter);
    }
    /**
     * Count (overridden).
     * 
     * Number of collected entities
     * @link http://www.php.net/manual/en/countable.count.php Countable Interface at php.net
     * @see \Openy\Interfaces\CollectionInterface Collection Interface
     */
    public function count(){
    	return $this->getAdapter()->count();
    }

    /**
     * {@inheritDoc}
     * @see Openy\Interfaces\CollectionInterface CollectionInterface
     */
    public function getEntities(){
        return $this->getAdapter()->getItems(0,$this->getTotalItemCount());
    }

    /**
     * {@inheritDoc}
     * @see Openy\Interfaces\CollectionInterface CollectionInterface
     */
    public function getEntitiesIds(){
        $entities = $this->getEntities();
        $result = array();
        foreach($entities as $entity)
            if ($entity instanceof AbstractEntity){
                $pk = $entity->getPK();
                $result[] = $entity->{$pk};
            }
        return array_unique($result);
    }
    
    /**
     * {@inheritDoc}
     * @see Openy\Interfaces\CollectionInterface CollectionInterface
     */
    public function getEntitiesProperty($property){
        $entities = $this->getEntities();
        $result = array();
        foreach($entities as $entity)
            if (property_exists($entity, $property)){
                $result[] = $entity->{$property};
            }
        return array_unique($result);
    }

}