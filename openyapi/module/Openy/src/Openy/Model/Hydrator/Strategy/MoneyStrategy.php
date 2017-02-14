<?php
namespace Openy\Model\Hydrator\Strategy;

use Openy\Model\Hydrator\Strategy\NumericVarcharStrategy;

class MoneyStrategy extends NumericVarcharStrategy
{
    protected $coin_char;
    protected $coin_position;

    const COIN_AT_RIGHT = 1;
    const COIN_AT_LEFT  = 2;

    public function __construct($coin_char = '€', $decimals = 2, $decimalSign = ".", $coin_position = self::COIN_AT_RIGHT){
        parent::__construct($decimals, $decimalSign);
        $this->coin_char = $coin_char;
        $this->coin_position = $coin_position;
    }

    public function extract($value)
    {
        $result = parent::hydrate($value); //NumericVarcharStrategy::hydrate returns a string
        if ($this->coin_position == self::COIN_AT_LEFT)
            $result .= $this->coin_char;
        else
            $result = $this->coin_char.$result;
        return $result;
    }

    public function hydrate($value)
    {
        $value = preg_replace('/'.$this->coin_char.'/', '', $value);
        $result = parent::extract($value); //NumericVarcharStrategy::extract returns a number
        return $result;
    }
}
