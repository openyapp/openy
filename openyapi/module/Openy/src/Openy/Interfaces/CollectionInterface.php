<?php
/**
 * Interface.
 * Contains interface for AbstractCollection and descendants
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core
 * @category Classes
 *
 */
namespace Openy\Interfaces;

use \Countable;
use \IteratorAggregate;
use Zend\Filter\FilterInterface;

/**
 * CollectionInterface.
 * Defines methods emulating Zend Paginator, 
 * and defines methods for importing and exporting arrays of Abstract Entities  
 * 
 * @see \Zend\Paginator\Paginator Zend Paginator
 * @see \Openy\Model\AbstractEntity Abstract Entity
 * @uses \Countable
 * @uses \IteratorAggregate
 */
interface CollectionInterface
	extends Countable, IteratorAggregate
{

    /**
     * Builds a Collection with provided entities
     * @param \Zend\Paginator\Adapter\AdapterInterface | array $entities The source of data to be collected
     */
    public function __construct($entities);
	
    /**
     * Serializes the collection as a string. 
     * @see \Zend\Paginator\Paginator Zend Paginator
     * @return string
     */
    public function __toString();

    /**
     * Returns the items of the current page as JSON.
     * @see \Zend\Paginator\Paginator Zend Paginator
     * @return string
     */
    public function toJson();
    
    /**
     * Returns the total number of items available.
     * @see \Zend\Paginator\Paginator Zend Paginator
     * @return int
     */
    public function getTotalItemCount();

    /**
     * Returns the adapter.
     * @see \Zend\Paginator\Paginator Zend Paginator
     * @return AdapterInterface
     */
    public function getAdapter();

    /**
     * Returns an array populated with all collected entities
     * @return Array of \Openy\Model\AbstractEntity
     */
    public function getEntities();

    /**
     * Returns an array populated with the identifiers of all collected entities
     * @return Array of Mixed
     * @see \Openy\Model\AbstractEntity::getId Abstract Entity getId function
     */
    public function getEntitiesIds();
    
    /**
     * Returns an array populated with the values of a property for all collected entities
     * @return Array of Mixed
     * 
     */
    public function getEntitiesProperty($property);    
    
    /**
     * Get the filter
     * @see \Zend\Paginator\Paginator Zend Paginator
     * @return FilterInterface
     */
    public function getFilter();

    /**
     * Set a filter chain
     * @see \Zend\Paginator\Paginator Zend Paginator
     * @param  FilterInterface $filter
     * @return CollectionInterface
     */
    public function setFilter(FilterInterface $filter);

    /**
     * Returns the number of items in a collection.
     * @see \Zend\Paginator\Paginator Zend Paginator
     * @param  mixed $items Items
     * @return int
     */
    public function getItemCount($items);




}