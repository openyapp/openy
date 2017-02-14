<?php
/**
 * Mapper trait.
 * Provides getter and setter for a property of a MapperInterface kind
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core
 * @see Openy\Interfaces\MapperInterface
 * @see Openy\Service\AbstractService
 *
 */
namespace Openy\Traits\Properties;

use Openy\Interfaces\MapperInterface;

/**
 * Options Property Interface.
 * Implements Interfaces\Properties\MapperInterface
 *
 * @uses Openy\Interfaces\MapperInterface Mapper Interface
 */

trait MapperTrait{
	/**
	 * Mapper property.
	 * @see \Openy\Traits\Properties\MapperTrait Mapper Property Trait
	 * @var MapperInterface
	 */
    protected $mapper;

    /**
     * Sets the Mapper property
     * @param MapperInterface $mapper
     * @return Mixed Service or Instance containing the Mapper
	 * @see \Openy\Traits\Properties\MapperTrait Mapper Property Trait 
     */
    public function setMapper(MapperInterface $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }
    /**
     * Gets the mapper
     * @return MapperInterface
	 * @see \Openy\Traits\Properties\MapperTrait Mapper Property Trait 
     */
    public function getMapper()
    {
        return $this->mapper;
    }
}