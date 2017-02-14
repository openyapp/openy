<?php
namespace Openy\Model\Hydrator\Strategy;

use Openy\Model\Hydrator\Strategy\NumericVarcharStrategy;

class NumberStrategy extends NumericVarcharStrategy
{

    public function __construct($decimals = 2, $decimalSign = "."){
        parent::__construct($decimals, $decimalSign);
    }

    public function extract($value)
    {
        return parent::hydrate($value); //NumericVarcharStrategy::hydrate returns a string
    }

    public function hydrate($value)
    {
        return parent::extract($value); //NumericVarcharStrategy::extract returns a number
    }
}
