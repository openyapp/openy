<?php

namespace Openy\Traits\Classes;
use \InvalidArgumentException;

/**
 * Class function called inherit populates object with a parent instance
 * @link(http://blog.jasny.net/articles/a-dark-corner-of-php-class-casting/, Explanatory blog post)
 * @link (https://gist.github.com/duaiwe/960035, Gist about class typecasting between descendants)
 */
trait InheritTrait
{

	public static function inherit($parentEntity){
		if( !is_object($parentEntity) )
			throw new InvalidArgumentException('$parentEntity must be an object.');
		if( !class_exists(get_class($parentEntity)) )
			throw new InvalidArgumentException(sprintf('Unknown parent class: %s.', get_class($parentEntity)));
		if (is_a($parentEntity, __CLASS__))
			return clone $parentEntity;
		elseif( !is_subclass_of(__CLASS__, get_class($parentEntity)) )
			throw new InvalidArgumentException(sprintf(
				'%s is not a descendant of $object class: %s.',
				__CLASS__, get_class($parentEntity)
			));
		$obj = unserialize(
				preg_replace(
					'/^O:\d+:"[^"]++"/',
					'O:'.strlen(__CLASS__).':"'.__CLASS__.'"',
					serialize($parentEntity)
				)
			);
		return $obj;
	}
}
