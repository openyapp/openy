<?php

namespace ElemViewhelpers\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Sitevars extends AbstractHelper
{

    protected $sitevars;
   
    public function __construct($sitevars)
    {
        $this->sitevars = $sitevars;
    }
    
    public function __invoke($key = null)
    {
        if(!$key)
            return;
        if(!isset($this->sitevars[$key]))
            return $key;
        $output = sprintf("%s", $this->sitevars[$key]);
        return htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
    }

   
}
