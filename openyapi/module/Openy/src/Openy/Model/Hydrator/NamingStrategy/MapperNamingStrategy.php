<?php
/**
 * MapperNamingStrategy.
 * Naming Strategy for Model Hydrators
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core\Hydration
 *
 */
namespace Openy\Model\Hydrator\NamingStrategy;

use Zend\Stdlib\Hydrator\NamingStrategy\NamingStrategyInterface;

/**
 * Mapper Naming Strategy.
 * Translate attribute names when hydrating or extracting
 *
 * @uses http://framework.zend.com/apidoc/2.4/classes/Zend.Stdlib.Hydrator.NamingStrategy.NamingStrategyInterface.html Zend Naming Strategy Interface (implemented)
 *
 */
class MapperNamingStrategy implements NamingStrategyInterface
{
    /**
     * Attribute name translations
     * @var array
     */
    private $hydrateMap;

    /**
     * Constructor
     *
     * Stores translations to be applied when hydrating or extracting values
     *
     * @param array $hydrateMap Array containing the attribute name translations (from "keys" to "values" and viceversa)
     *
     */
    public function __construct(array $hydrateMap = [])
    {
        $this->hydrateMap = $hydrateMap;
    }

    /**
     * Converts the given name so that it can be extracted by the hydrator.
     *
     * @param string $name   The original name
     * @param object $object (optional) The original object for context.
     * @return mixed         The hydrated name
     */
    public function hydrate($name)
    {
        if (array_key_exists($name, $this->hydrateMap))
        {
            return $this->hydrateMap[$name];
        }
        return $name;
    }

    /**
     * Converts the given name so that it can be hydrated by the hydrator.
     *
     * @param string $name The original name
     * @param array  $data (optional) The original data for context.
     * @return mixed The extracted name
     */
    public function extract($name)
    {
        $flippedMap = array_flip($this->hydrateMap);

        if (array_key_exists($name, $flippedMap))
            return $flippedMap[$name];
        else
            return $name;
    }
}

