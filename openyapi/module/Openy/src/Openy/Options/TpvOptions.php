<?php

namespace Openy\Options;

use Zend\Stdlib\AbstractOptions;
use Openy\Options\SubOptions;

class TpvOptions extends SubOptions
{

    public function getSoap($arg=null){
        $env = "remote";
        $result = false;

        $soap = new SubOptions($this->options['soap']);

        if ($_SERVER['APPLICATION_ENV'] == 'development') {
            if (isset($soap['dev_env']))
                $env = $soap['dev_env'];
        }
        elseif (isset($soap['prod_env']))
                $env = $soap['prod_env'];

        if (isset($soap['environments'])){
            if (isset($soap['environments'][$env]))
                $result = $soap['environments'][$env];
        }
        if ($result)
            if (!is_null($arg))
                return $result[$arg];
            else
                return $result;
        else
            return parent::getSoap('remote');
    }

}