<?php
/**
 * AbstractEntity.
 * Base class for descending Model Entities
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core
 * @category Classes
 *
 */

namespace Openy\Model;

/**
 * Openy Abstract Base Entity
 * Provides constants for querying entities and fetching samples
 *
 */
abstract class AbstractEntity{

    /**
     * Primary key for Entity.
     * @const string
     * @ignore
     */
    const pk = 'id';

    /**
     * JSON sample for Entity.
     * @const string
     * @ignore
     */
    const sample =  <<<HEREDOC
    {
    	"id" : null
    }
HEREDOC;

    /**
     * Constructor.
     * @param mixed $id Primary Key value (identifying the instance)
     * @uses  pk pk class constant
     *
     */
	public function __construct($id=null){
		if (!is_null(static::pk)){
			$this->{static::pk} = $id;
		}
	}

    /**
     * Get a sample instance.
     * @return AbstractEntity Returns AbstractEntity descendant of __SELF__ class filled with data provided in sample constant
     * @uses  AbstractEntity::sample sample class constant
     */
	public function getSample(){
        $class = new \ReflectionClass(get_called_class());
        $entity = $class->newInstance();
		$data = \Zend\Json\Json::decode(!is_null(static::sample) ? static::sample : "{}", \Zend\Json\Json::TYPE_ARRAY);
        $hydrator = new \Zend\Stdlib\Hydrator\ObjectProperty;
        $entity = $hydrator->hydrate($data,$entity);
        if (array_key_exists('_embedded', $data)){
        	$embedded = (array)$data['_embedded'];
        	foreach(array_keys($embedded) as $prop)
        		if (property_exists($entity,$prop))
        			try{
        				unset($entity->{$prop});
        			}catch(\Exception $e){
        				// What to do?
        			}
        }
        return $entity;
	}

    /**
     * Get the Primary Key name
     * @return String Returns name for primary key attribute
     * @uses  self::pk pk class constant
     */
	public function getPK(){
		return (!is_null(static::pk)) ? static::pk : null;
	}


    /**
     * Get the Id (aka Primary Key) attribute value
     * @return  mixed The value contained in the primary key attribute
     * @uses  self::getPK() getPK function
     */
    public function getId(){
        return ($this->{$this->getPK()});
    }

}