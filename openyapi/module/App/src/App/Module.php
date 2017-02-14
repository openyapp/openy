<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace App;

use Zend\Mvc\ModuleRouteListener;

use Zend\ModuleManager\ModuleManager;
use Zend\Loader\StandardAutoloader;
use Zend\Loader\AutoloaderFactory;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Console\Adapter\AdapterInterface;



class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    BootstrapListenerInterface
{

    
    public function onBootstrap(EventInterface $event)
//     public function onBootstrap(MvcEvent $e)
    {
//         $translator = $e->getApplication()->getServiceManager()->get('translator');
//         $translator->addTranslationFile('phpArray', __DIR__, '/../../vendor/zendframework/zendframework/'.
//                                                     'resources/languages/es/Zend_Validate.php',
//                                                     'default',
//                                                     'es_ES'
//                                         );
        
//         AbstractValidator::setDefaultTranslator($translator);
        
//         $eventManager        = $e->getApplication()->getEventManager();
        $eventManager = $event->getTarget()->getEventManager();
        
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        
//         $this->getAclNavigation($event);
//         $eventManager->attach('route', array($this, 'loadConfiguration'), 2);
        
        
//         $app = $e->getParam('application');
//         $app->getEventManager()->attach('render', array($this, 'setLayoutTitle'));
        
       
        
        
    }
    
    public function loadConfiguration(MvcEvent $e)
    {
        $application   = $e->getApplication();
        $sm            = $application->getServiceManager();
        $sharedManager = $application->getEventManager()->getSharedManager();
         
        $router = $sm->get('router');
        $request = $sm->get('request');
         
        $matchedRoute = $router->match($request);
        if (null !== $matchedRoute) {
            $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController','dispatch',
                function($e) use ($sm) {
                    $sm->get('ControllerPluginManager')->get('AdminAcl')
                                                       ->doAuthorization($e); //pass to the plugin...
                },2
            );
        }
    }
    
    public function init(ModuleManager $mm)
    {
        // To change layout
//         $mm->getEventManager()->getSharedManager()->attach(__NAMESPACE__,
//             'dispatch', function($e) {
//                 $e->getTarget()->layout('admin/layout');
//             });
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

//     public function getAutoloaderConfig()
//     {
//         return array(
//             'Zend\Loader\StandardAutoloader' => array(
//                 'namespaces' => array(
//                     __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
//                 ),
//             ),
//         );
//     }  
    
    /**
     * {@inheritDoc}
     */
    
    public function getControllerPluginConfig()
    {
        return array(
            'factories' => array(
                'AdminAcl' => 'Admin\Controller\Plugin\AclFactory'
            ),
        );
        
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            AutoloaderFactory::STANDARD_AUTOLOADER => array(
                StandardAutoloader::LOAD_NS => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
        
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Admin\Model\StationMapper' => function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $mapper = new \Admin\Model\StationMapper($adapterMaster);
                    return $mapper;
                },
                'Admin\Model\PriceMapper' => function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $mapper = new \Admin\Model\PriceMapper($adapterMaster);
                    return $mapper;
                }
            ),
        );
    }
    
    public function setLayoutTitle($e)
    {
        $matches    = $e->getRouteMatch();
        $action     = $matches->getParam('action');
        $controller = $matches->getParam('controller');
        $module     = __NAMESPACE__;
        $siteName   = 'Openyapi';
    
        // Getting the view helper manager from the application service manager
        $viewHelperManager = $e->getApplication()->getServiceManager()->get('viewHelperManager');
    
        // Getting the headTitle helper from the view helper manager
        $headTitleHelper   = $viewHelperManager->get('headTitle');
    
        // Setting a separator string for segments
        $headTitleHelper->setSeparator(' - ');
    
        // Setting the action, controller, module and site name as title segments
//         $headTitleHelper->append($action);
//         $headTitleHelper->append($controller);
//         $headTitleHelper->append($module);
        $headTitleHelper->append($siteName);
        
        
        
        
        
    }
    
    protected function getAclNavigation(MvcEvent $event)
    {
        $app = $event->getApplication();
        $sm  = $app->getServiceManager();
        $em  = $app->getEventManager();
         
        $users = $sm->get('ElemSqliteauth\Service')->getStorage()->read();
//         echo "<pre>";
//         print_r($users);
//         echo "</pre>";
        $navigationAcl = $this->setAcl('1');
        
//         echo "<pre>";
//         print_r($navigationAcl);
//         echo "</pre>";
        
        $userauth = $sm->get('userauth');
        //         echo "<pre>";
        //         print_r($userauth->getPages());
        //         echo "</pre>";
        
        
        //         $page3 = $userauth->findOneByAction('Developers');
        //         $userauth->removePage($page3); // removes Page 3
        //         echo "<pre>";
        //                 print_r($page3);
        //                 echo "</pre>";
        
        //         $containers = $navHelper->getContainer();
        foreach($userauth->getPages() as $page)
        {
            
            if(in_array($page->getHref(), $navigationAcl))
            {
//                 echo "kaka: ";
//                 echo $page->getHref();
//                 echo "<br/>\n";
//                 echo $page->getLabel();
            }
                
            else 
            {
//                 echo "no kaka: ";
//                 echo $page->getHref();
//                 echo "<br/>\n";
                
                $page3 = $userauth->findOneByAction('Developers');
                $userauth->removePage($page3); // removes Page 3
                
                
//                 echo $page->getLabel();
            }
                
            
        }
        
      
        
//         $viewHelperManager = $sm->get('viewHelperManager');
        
        // Getting the headTitle helper from the view helper manager
//         $headTitleHelper   = $viewHelperManager->get('navigation');
        
//         echo "<pre>";
//         print_r($headTitleHelper->getPages());
//         echo "</pre>";
    }
    
    protected function setAcl($idrol = null)
    {
        /*
         * Roles
         * 1 = developer
         * 2 = apidevel
         * 3 = viewer
         *
         */
        
        $navigationAcl = array ('1'=>array('/success','/auth/logout','/developers'));
        
        if(isset($idrol))
            return $navigationAcl[$idrol];
        else
            return $navigationAcl;
    }
    
    /**
     * @param \Zend\Console\Adapter\AdapterInterface $console
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return array(
            'Admin',
            'import gasstation <filename> [--verbose|-v]' => 'Import Gas Stations from filename.json',
            array('--verbose | -v', '(optional) verbose mode'),
            array('<filename>', 'filename .json'),
            
            

            'GetMinetur:',
            'getminetur <type> [--verbose|-v]' => 'Get station info from MineTur',
            array('--verbose | -v', '(optional) verbose mode'),
            array('<type>', 'GasStationPrice | PostesMaritimos | EstacionesTerrestres'),
            
            'Address:',
            'import address'     => 'Import gas station address with locality',
            array(),
            array(),
            
            'Municipality:',
            'import municipality'     => 'Import municipies',
            
            'Locality:',
            'import locality'     => 'Import localities',
            
//             'MonitorRaise:',
//             'monitor raise'     => 'Monitor Raise hosepipe at idopystation/pump',
        );
    }
}
