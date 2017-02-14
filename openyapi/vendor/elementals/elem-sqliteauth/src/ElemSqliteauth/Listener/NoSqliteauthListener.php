<?php
namespace ElemSqliteauth\Listener;

use Zend\Mvc\MvcEvent;

class NoSqliteauthListener
{
    public function __invoke(MvcEvent $event)
    {
//         echo "2";
        $event->setParam('enableSqliteauth', false);
//         if (!$event->getApplication()->getServiceManager()->get('ElemAuth\Service')->hasIdentity()) 
//         {
//             $controller = $event->getTarget();
//             $controller->redirect()->toRoute('auth/login');
//         }
    }
}