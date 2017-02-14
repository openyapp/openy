<?php
namespace Openy\Authentication\Listener;

use Openy\Authentication\Adapter\HeaderAuthentication;
use Zend\Mvc\MvcEvent;
use ZF\MvcAuth\MvcAuthEvent;

class ApiAuthenticationListener
{
    protected $adapter;

    public function __construct(HeaderAuthentication $adapter) 
    {
        $this->adapter = $adapter;
    }


//     public function __invoke(MvcEvent $event)
    public function __invoke(MvcAuthEvent $event)
    {
        $eventMvc = $event->getMvcEvent();
        $result = $this->adapter->authenticate();

//         print_r($result->getStatusCode());
//         if($result->getStatusCode()==200)
            return $result; 
        
//         if (!$result->isValid()) {
//             $response = $eventMvc->getResponse();

//             // Set some response content
//             $response->setStatusCode(401);
//             return $response;
//         }

        // All is OK
//         $event->setParam('user', $result->getIdentity());
    }
}