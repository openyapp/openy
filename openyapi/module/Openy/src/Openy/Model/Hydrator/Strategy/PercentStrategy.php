<?php
namespace Openy\Model\Hydrator\Strategy;

use Openy\Model\Hydrator\Strategy\MoneyStrategy;

class PercentStrategy extends MoneyStrategy
{

    const COIN_AT_RIGHT = 1;
    const COIN_AT_LEFT  = 2;

    public function __construct(){
        parent::__construct($coin_char = '%', $decimals = 0);
    }
}
