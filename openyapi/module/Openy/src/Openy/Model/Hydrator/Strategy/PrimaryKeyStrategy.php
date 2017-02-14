<?php
/**
 * Primary Key Strategy.
 * Hydrator Strategy for Primary Key fields or Identifier fields
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core\Hydration
 *
 */
namespace Openy\Model\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;
use Zend\Stdlib\AbstractOptions;

/**
 * Abstract PrimaryKey Strategy.
 *
 * Identifies family of Strategies indented for Primary Key fields and identifiers
 *
 * @uses http://framework.zend.com/apidoc/2.4/classes/Zend.Stdlib.AbstractOptions.html Zend AbstractOptions
 * @see http://framework.zend.com/apidoc/2.4/classes/Zend.Stdlib.Hydrator.Strategy.DefaultStrategy.html Zend Default Hydrator Strategy
 *
 */
abstract class PrimaryKeyStrategy extends DefaultStrategy
{
    /**
     * Options used when hydrating or extracting.
     * Available on PrimaryKeyStrategy descendants
     *
     * @var AbstractOptions
     * @internal
     */
	protected $options;

    /**
     * Constructor
     *
     * @uses  Openy\Options\OpenyOptions OpenyOptions as $options param
     * @param Zend\Stdlib\AbstractOptions $options Options used by descendants
     *
     */
	public function __construct(AbstractOptions $Options){
		$this->options = $Options;
	}

}
