<?php

namespace Openy\Traits\Properties;

trait ServicePropertiesGetterTrait{
    public function __call($function,$args){
        if (preg_match("/Service$/",$function)){
            $function = preg_replace("/^get/", '', $function);
            $property = lcfirst($function);
            if ( property_exists($this, $property)){
                return $this->{$property};
            }
        }
        return null;
    }
}