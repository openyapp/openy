<?php
namespace ElemSqliteauth\Listener;

use Zend\Mvc\MvcEvent;

class SqliteauthListener
{
    public function __invoke(MvcEvent $event)
    {
//         echo "3";
        $serviceManage = $event->getApplication()->getServiceManager();
        $enableAuth = $event->getParam('enableSqliteauth'); 
//         \Zend\Debug\Debug::dump($enableAuth, "enableAuth: ");
        if($enableAuth)
            if (!$serviceManage->get('ElemSqliteauth\Service')->hasIdentity()) 
            {
                $controller = $event->getTarget();
                $controller->redirect()->toRoute('login');
            }
            else
            {
                //echo "Si auth";
                //$user = $serviceManage->get('ElemAuth\Service')->getAuthIdentity();
                //\Zend\Debug\Debug::dump($user, "user: ");
            }    
//             die;    
    }
}