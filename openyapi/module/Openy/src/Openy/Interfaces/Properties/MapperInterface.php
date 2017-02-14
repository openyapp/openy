<?php
/**
 * Mapper Interface.
 * Defines getter and setter for a property of a MapperInterface kind
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core
 * @see Openy\Interfaces\MapperInterface
 * @see Openy\Service\AbstractService
 *
 */
namespace Openy\Interfaces\Properties;

use Openy\Interfaces\MapperInterface as Mapper;

/**
 * Options Property Interface.
 * Defines getter and setter for a property of an OpenyOptions kind
 * 
 * @uses Openy\Interfaces\MapperInterface Mapper Interface
 */
interface MapperInterface
{
	/**
	 * Sets the mapper
	 * @param MapperInterface $mapper
	 * @return Mixed Service or Instance containing the Mapper
	 * @uses MapperInterface Mapper Interface
	 */
    public function setMapper(Mapper $mapper);
    /**
     * Gets the mapper
     * @return Mapper
     */
    public function getMapper();
}