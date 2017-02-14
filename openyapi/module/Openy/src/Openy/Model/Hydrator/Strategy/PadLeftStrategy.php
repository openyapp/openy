<?php
namespace Openy\Model\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

class PadLeftStrategy extends DefaultStrategy
{
    protected $pad_length;
    protected $pad_string;

    public function __construct($pad_length,$pad_string){
        $this->pad_string = $pad_string;
        $this->pad_length = $pad_length;
    }

    public function extract($value)
    {
        //string ltrim ( string $str [, string $character_mask ] )
        return ltrim($value,$this->pad_string);
    }

     public function hydrate($value)
     {
        // str_pad ( string $input , int $pad_length [, string $pad_string = " " [, int $pad_type = STR_PAD_RIGHT ]] )
        return str_pad($value,$this->pad_length,$this->pad_string, STR_PAD_LEFT);
     }

}
