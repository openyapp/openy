<?php

namespace Openy\Model\Hydrator;

use Zend\Stdlib\Hydrator\ObjectProperty;
use Openy\Model\Hydrator\Strategy\CurrentTimestampStrategy;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;

class TransactionHydrator extends ObjectProperty
{

    /**
     * {@inheritDoc}
     *
     * By default object will fill its created field with current datetime if empty.
     * It will do the same when created has a value but not updated
     *
     * @throws Exception\BadMethodCallException for a non-object $object
     */
    public function extract($object)
    {                     
    	if (is_null($object->created))
    		$this->addStrategy('created', new CurrentTimestampStrategy('Y-m-d H:i:s'));
    	elseif (is_null($object->updated))
    		$this->addStrategy('updated', new CurrentTimestampStrategy('Y-m-d H:i:s'));

        $result = parent::extract($object);
        return $result;      
    }


    /**
     * {@inheritDoc}
     *
     * Sets created field with current datetime if empty
     *
     * @throws Exception\BadMethodCallException for a non-object $object
     */
    public function hydrate(array $data, $object)
    {
    	if (!array_key_exists('created', $data) || (is_null($data['created'])) 
            && is_null($object->created)){            
    		$this->addStrategy('created', new CurrentTimestampStrategy('Y-m-d H:i:s'));
        }
        return parent::hydrate($data,$object);        
    }



}
