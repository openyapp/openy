<?php

namespace Openy\Model\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

/**
 * Replaces any non time value with Current time
 */
class NumericVarcharStrategy extends DefaultStrategy
{
	protected $decimals;
	protected $decimalSign;

	public function __construct($decimals = 2, $decimalSign = ","){	
		$this->decimals = $decimals;
		$this->decimalSign = $decimalSign;
	}

	public function extract($value){
		$value = str_replace($this->decimalSign, '.', $value);		
		return (float)$value;
	}

	public function hydrate($value){
		if (is_numeric($value) && !is_string($value))
			$value = number_format($value,$this->decimals,$this->decimalSign,'');
		return (string)$value;
	}


}
