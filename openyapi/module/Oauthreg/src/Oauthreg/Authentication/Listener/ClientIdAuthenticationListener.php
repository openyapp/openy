<?php
namespace Oauthreg\Authentication\Listener;

use Oauthreg\Authentication\Adapter\ClientIdAuthentication;
use ZF\MvcAuth\MvcAuthEvent;

class ClientIdAuthenticationListener
{
    protected $adapter;

    public function __construct(ClientIdAuthentication $adapter) 
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