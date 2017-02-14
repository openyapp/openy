<?php

namespace Openy\Model\Hydrator\Strategy;

use Openy\Model\Hydrator\Strategy\UnsignedIntZeroPadStrategy;

/**
 * Cast Float to a chain of numbers where two last digits are meant to be decimals
 */
class DsAmountStrategy extends UnsignedIntZeroPadStrategy
{

	public function __construct(){
		return parent::__construct($pad_length = 12, $padding_behaviour = self::PAD_WHEN_EXTRACTING);
	}
    public function extract($value){
        $value =  intval(round(floatval($value)*100));
        return parent::extract($value);
    }

    public function hydrate($value){
    	if (is_float($value))
            return floatval($value);

    	$value = intval($value) / 100;

        //BUGFIX
        $value = number_format($value,2,'.','');
        //WARNING: DO NOT REMOVE THIS LINE CAUSE FIXES A PHP BUG

    	$value = parent::hydrate($value);
        return $value;

    }

}