<?php
namespace ElemSqliteauth;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
// use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as DbTableAuthAdapter;
use Zend\Mvc\MvcEvent;
use Zend\Db\Adapter\Adapter;

class Module implements AutoloaderProviderInterface
{       
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }      
    
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
        
   
     
    public function loadCommonViewVars(MvcEvent $e)
    {
        $e->getViewModel()->setVariables(array('loginbtn' => ($e->getApplication()
                                                                 ->getServiceManager()
                                                                 ->get('ElemSqliteauth\Service')
                                                                 ->hasIdentity()) ? True : False
                                               )
                                        );
    }
    
    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getApplication();
        $sm  = $app->getServiceManager();
        $eventManager  = $app->getEventManager();
        $shareManager = $eventManager->getSharedManager();
        $event->setParam('enableSqliteauth', true);         
    
        $listenerNoauth = $sm->get('NoElemSqliteauth\Listener');
        $listenerAuth = $sm->get('ElemSqliteauth\Listener');
        $listenerEnableAuth = $sm->get('EnableSqliteauth\Listener');
        
        $eventManager->attach(MvcEvent::EVENT_ROUTE, $listenerEnableAuth, -80);   // Enable validation under madule position
        $shareManager->attach('NoSqliteauth', MvcEvent::EVENT_DISPATCH, $listenerNoauth);           // Ignore validation with NoAuth
        // $shareManager->attach('Sqliteauth', MvcEvent::EVENT_DISPATCH, $listenerAuth);            // Force validation with Auth
        $eventManager->attach(MvcEvent::EVENT_DISPATCH,$listenerAuth,-80);
        $eventManager->attach('dispatch', array($this, 'loadCommonViewVars'), 100);
        
        // Match router
//         $router = $sm->get('router');
//         $request = $sm->get('request');
//         $matchedRoute = $router->match($request);
//         $route = $matchedRoute->getMatchedRouteName();
        
//         \Zend\Debug\Debug::dump($matchedRoute, "matchedRoute: ");
//         \Zend\Debug\Debug::dump($route, "matchedRoute: ");
        
        // List modules
//         $manager = $sm->get('ModuleManager');
//         $modules = $manager->getLoadedModules();
//         $loadedModules      = array_keys($modules);
//         \Zend\Debug\Debug::dump($loadedModules, "loadedModules: ");
        
//         $current = array_search('ElemSqliteauth', $loadedModules);
        
//         \Zend\Debug\Debug::dump($current, "current: ");
                
    }
        
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'sqliteauth_module_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\ModuleOptions(isset($config['sqliteauth']) ? $config['sqliteauth'] : array());
                },
                
                'ElemSqliteauth\Authentication\Storage\StorageAuth' => function($sm){
                    return new \ElemSqliteauth\Authentication\Storage\StorageAuth('storageAuth');
                },
                
                'ElemSqliteauth\Service' => function($sm) {
                //My assumption, you've alredy set dbAdapter
                //and has users table with columns : user_name and pass_word
                //that password hashed with md5
//                 $dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
                
                $dbAdapter = new Adapter(array(
                    'driver' => 'Pdo_Sqlite',
                    'database' => 'data/sqliteauth'
                ));
                
                $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter,
                    'users','username','password');
                
                //$dbTableAuthAdapter->setCredentialTreatment('PASSWORD(?)');
                 
                
                $authService = new AuthenticationService();
                $authService->setAdapter($dbTableAuthAdapter);
                $authService->setStorage($sm->get('ElemSqliteauth\Authentication\Storage\StorageAuth'));
                
                return $authService;
                },
                
                'NoElemSqliteauth\Listener' => function($sm) {
                    $listener = new Listener\NoSqliteauthListener();
                    return $listener;
                },
                'ElemSqliteauth\Listener' => function($sm) {
                    $listener = new Listener\SqliteauthListener();
                    return $listener;
                },
                
                'EnableSqliteauth\Listener' => function($sm) {
                    $listener = new Listener\EnableSqliteauthListener();
                    return $listener;
                },
                
//                 'ElemSqliteauth\Service' => 'ElemSqliteauth\Service\ApiCallerFactory',                
            ),
        );
    }
    
    public function getControllerPluginConfig()
    {
        return array(
    
            'factories' => array(
                'authAuthentication' => function($sm) {
                    $authService = $sm->getServiceLocator()->get('ElemAuth\Service');
                    $authAdapter = $sm->getServiceLocator()->get('ElemAuth\Authentication\Adapter\Api');
                    $controllerPlugin = new AuthAuthentication;
                    $controllerPlugin->setAuthService($authService)
                    ->setAuthAdapter($authAdapter);
                    return $controllerPlugin;
                },
            ),
        );
    }
    
    
  
    
    
}