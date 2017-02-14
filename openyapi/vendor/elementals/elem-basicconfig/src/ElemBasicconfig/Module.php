<?php
namespace ElemBasicconfig;

use ElemBasicconfig\View\Helper\Sitevars;
use Zend\Mvc\MvcEvent;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class Module 
{   
    public function getServiceConfig()
    {
        return array(
            'initializers' => [
                'ElemBasicconfig\Initializer\LoggerServiceInitializer'
            ],
            'factories' => array(
                'Log\General' => function ($sm) {
                    $log = new Logger();
                    $writer = new Stream('./data/logs/general.log');
                    $log->addWriter($writer);
            
                    return $log;
                },
            ),
        );
    }
}