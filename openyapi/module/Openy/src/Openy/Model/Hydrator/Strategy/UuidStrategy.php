<?php
/**
 * Uuid Strategy.
 * Hydrator Strategy for UUID fields
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core\Hydration
 *
 */
namespace Openy\Model\Hydrator\Strategy;

use Openy\Model\Hydrator\Strategy\PrimaryKeyStrategy;
use Rhumsaa\Uuid\Uuid;


/**
 * CurrentTimestamp Strategy.
 *
 * Sets an UUID for null values when hydrating
 *
 * @uses  Rhumsaa\Uuid\Uuid Rhumsaa\Uuid library's Uuid class
 * @see https://github.com/ramsey/uuid Ramsey/Uuid project homepage
 * @uses  Openy\Model\Hydrator\Strategy\PrimaryKeyStrategy Primary Key Strategy (parent)
 */
class UuidStrategy extends PrimaryKeyStrategy
{

    /**
     * Hydrate
     *
     * Initializes null values with an UUID string
     *
     * @param  mixed $value Value to be hydrated when null
     * @return String
     */
	public function hydrate($value){
		$value = (string)$value;
		$value = trim($value);
		if (empty($value)){
			$value = (string)Uuid::uuid5($this->options->getDnUuid(), microtime());
		}
		return parent::hydrate($value);
	}


}
