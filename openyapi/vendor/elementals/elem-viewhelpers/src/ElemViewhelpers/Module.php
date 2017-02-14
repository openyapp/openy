<?php
namespace ElemViewhelpers;

use ElemViewhelpers\View\Helper\Sitevars;
use Zend\Mvc\MvcEvent;

class Module 
{   
    public function onBootstrap(MvcEvent $event)
    {   
        $app = $event->getApplication();
        //$sm  = $app->getServiceManager();
        $em  = $app->getEventManager();
        $sem  = $em->getSharedManager();
        $sem->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', array($this, 'setLayoutByModule'),100);
        $sem->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', array($this, 'setLayoutByController'),100);
    }
    
    public function setLayoutByModule($e)
    {  
        $controller      = $e->getTarget();
        $controllerClass = get_class($controller);
        $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        $config          = $e->getApplication()->getServiceManager()->get('config');
        if (isset($config['module_layouts'][$moduleNamespace])) {
            $controller->layout($config['module_layouts'][$moduleNamespace]);
        }    
    }
    
    public function setLayoutByController($e)
    {
        $controller      = $e->getTarget();
        $controllerClass = get_class($controller);
        $ar = explode('\\',$controllerClass);
        $controllerStr = str_replace('Controller','',$ar[count($ar) -1]);
        $config          = $e->getApplication()->getServiceManager()->get('config');
        if (isset($config['controller_layouts'][$controllerClass])) {
            $controller->layout($config['controller_layouts'][$controllerClass]);
        }
    }
    
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
            // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }	
    
    public function getViewHelperConfig()
    {
    
        return array(
            'factories' => array(
                'sitevars' => function($sm) {
                    $config = $sm->getServiceLocator()->get('Config');
                    if(!isset($config['sitevars']))
                        $config['sitevars']=null;
                    $viewHelper = new Sitevars($config['sitevars']); 
                    return $viewHelper;
                },
            ),
        );
    }
}