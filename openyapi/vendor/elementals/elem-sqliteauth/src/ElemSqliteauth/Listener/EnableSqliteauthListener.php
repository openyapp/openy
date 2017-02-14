<?php
namespace ElemSqliteauth\Listener;

use Zend\Mvc\MvcEvent;

class EnableSqliteauthListener
{
    public function __invoke(MvcEvent $event)
    {
//         echo "1";
        $sm = $event->getApplication()->getServiceManager();
        $manager = $sm->get('ModuleManager');
        $modules = $manager->getLoadedModules();
        $loadedModules      = array_keys($modules);
//         \Zend\Debug\Debug::dump($loadedModules, "loadedModules: ");
        
        $authpos = array_search('ElemSqliteauth', $loadedModules);
        
//         \Zend\Debug\Debug::dump($authpos, "authpos: ");
                       
        $router = $sm->get('router');
        $request = $sm->get('request');
        $matchedRoute = $router->match($request);
        $route = $matchedRoute->getMatchedRouteName();

//         \Zend\Debug\Debug::dump($matchedRoute, "matchedRoute: ");
//         \Zend\Debug\Debug::dump($route, "route: ");
                
        $params = $matchedRoute->getParams();
        
        
        $controller = $params['controller'];
//         $action = $params['action'];
        
//         \Zend\Debug\Debug::dump($controller, "namespace: ");
        
        if(!isset($params['__NAMESPACE__']))
        {
            $namespace = explode('\\', $controller);
            unset($namespace[count($namespace)-1]);
        }
        else
        {
            $namespace = $params['__NAMESPACE__'];
            $namespace = explode('\\', $namespace);
            unset($namespace[count($namespace)-1]);            
        }         
        
        $module = $namespace[0];
//         $module = implode('\\', $namespace);        
//         \Zend\Debug\Debug::dump($namespace, "namespace: ");
//         \Zend\Debug\Debug::dump($module, "module: ");

        $route = $matchedRoute->getMatchedRouteName();
        
        $arr = array(
            'CURRENT_MODULE_NAME' => $module,
            'CURRENT_CONTROLLER_NAME' => $controller,
//             'CURRENT_ACTION_NAME' => $action,
            'CURRENT_ROUTE_NAME' => $route,
        );
        
        $modpos = array_search($module, $loadedModules);
//         \Zend\Debug\Debug::dump($modpos, "modpos: ");
//         \Zend\Debug\Debug::dump($module, "module: ");
        
        
        if($modpos<$authpos)
            $event->setParam('enableSqliteauth', false);
        else
            $event->setParam('enableSqliteauth', true);
        
        
        
        
//         $e->getViewModel()->setVariables(
//             $arr
//         );
        
//         \Zend\Debug\Debug::dump($arr, "arr: ");
        
        
//         $controller = $e->getTarget();
//         $controllerClass = get_class($controller);
//         $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        
        
        
        
//         $event->setParam('enableSqliteauth', false);
//         if (!$event->getApplication()->getServiceManager()->get('ElemAuth\Service')->hasIdentity()) 
//         {
//             $controller = $event->getTarget();
//             $controller->redirect()->toRoute('auth/login');
//         }
    }
}