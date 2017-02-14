<?php
namespace Oauthreg\Authentication\Listener;

use Oauthreg\Authentication\Adapter\CheckValidation;
use ZF\MvcAuth\MvcAuthEvent;

class CheckValidationListener
{
    protected $adapter;

    public function __construct(CheckValidation $adapter) 
    {
        $this->adapter = $adapter;
    }

    public function __invoke(MvcAuthEvent $event)
    {
        $eventMvc = $event->getMvcEvent();
        $result = $this->adapter->authenticate();
        return $result; 
    }
}