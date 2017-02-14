<?php

namespace Openy\Model\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

/**
 * Nulls any value
 */
class NullStrategy extends DefaultStrategy
{
    public function extract($value){
        return null;
    }

    public function hydrate($value){
        return null;
    }

}