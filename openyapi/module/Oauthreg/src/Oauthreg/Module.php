<?php
namespace Oauthreg;

use DomainException;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

use ZF\Apigility\Provider\ApigilityProviderInterface;
use Oauthreg\V1\Rest\Oauthuser\OauthuserMapper;
use Oauthreg\V1\Rest\Recoverpassword\RecoverpasswordMapper;
use Oauthreg\V1\Rest\Register\RegisterMapper;
use Oauthreg\Service\VerifyEmail;
use Oauthreg\Service\RegisterOptions;
use Zend\Mvc\MvcEvent;
use ZF\MvcAuth\MvcAuthEvent;
use Zend\Console\Request as ConsoleRequest;
use Zend\Http\Request as HttpRequest ;
use Zend\Log\Writer\Db as logDb;
use Zend\Log\Logger;
use Zend\Http\PhpEnvironment\RemoteAddress;

use Zend\Mvc\ModuleRouteListener;

// use Zend\Module\Manager;
// use Zend\EventManager\StaticEventManager;
// use Zend\Module\Consumer\AutoloaderProvider;

class Module implements ApigilityProviderInterface//, AutoloaderProvider
{
//     protected static $options;
    
//     public function init(Manager $moduleManager)
//     {
//         $moduleManager->events()->attach('loadModules.post', array($this, 'modulesLoaded'));
    
//         $sharedEvents = $moduleManager->events()->getSharedCollections();
//         $sharedEvents->attach('Zend\Mvc\Application', 'route', array($this, 'checkHTTPS'), -10);
//     }

    public function getAutoloaderConfig()
    {
        return array(
            'ZF\Apigility\Autoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                
                'Oauthreg\Service\RegisterOptions' => function ($sm) {
                    $config = $sm->get('Config');
                    return new RegisterOptions(isset($config['oauthreg']) ? $config['oauthreg'] : array());
                },
                
                'ApiKey' => function ($sm) {
                    $request = $sm->get('request');
                    if($request instanceof ConsoleRequest)
                        return false;
                    $headers = $request->getHeaders();
                    
                    $options        = $sm->get('Oauthreg\Service\RegisterOptions');
                    
                    if($options->getIsEnableXapikeyHeader())
                    {
                        if(empty($request->getQuery('X-ApiKey')))
                        {
                            $apikey = $headers->get('X-ApiKey')
                            ->getFieldValue();
                        }
                        else
                        {
                            $apikey = $request->getQuery('X-ApiKey');
                        }
                        return $apikey;
                    }
                    return false;
                },
                
                'Oauthreg\Service\CurrentUser' => function ($sm) {
                    $request        = $sm->get('Request');
                    $repository     = $sm->get('Oauthreg\V1\Rest\Oauthuser\OauthuserMapper');
                    return new \Oauthreg\Service\CurrentUser($request, $repository);
                },
                
                'Oauthreg\Service\VerifyEmail' => function ($sm) {
                    $options        = $sm->get('Oauthreg\Service\RegisterOptions');
                    $apikey         = $sm->get('ApiKey');
                    return new VerifyEmail($options, $apikey);
                },
                'Oauthreg\V1\Rest\Register\RegisterMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Oauthreg\Service\RegisterOptions');
                    $mailer         = $sm->get('Oauthreg\Service\VerifyEmail');
                    $apicaller      = $sm->get('ElemApicaller\Service');
                    return new RegisterMapper($adapterMaster, $adapterSlave, $options, $mailer, $apicaller);
                },
                
                'Oauthreg\V1\Rest\Recoverpassword\RecoverpasswordMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Oauthreg\Service\RegisterOptions');
                    $mailer        = $sm->get('Oauthreg\Service\VerifyEmail');
                    return new RecoverpasswordMapper($adapterMaster, $adapterSlave, $options, $mailer);
                },
                
                'Oauthreg\V1\Rest\Oauthuser\OauthuserMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Oauthreg\Service\RegisterOptions');
                    $request        = $sm->get('Request');
                    return new OauthuserMapper($adapterMaster, $adapterSlave, $options, $request);
                },
                
                'Oauthreg\V1\Rest\Clientregister\ClientregisterMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Oauthreg\Service\RegisterOptions');
                    return new \Oauthreg\V1\Rest\Clientregister\ClientregisterMapper($adapterMaster, $adapterSlave, $options);
                },
                
                
                'Oauthreg\Authentication\Listener\ClientIdAuthenticationListener'   => 'Oauthreg\Authentication\Factory\ClientIdAuthenticationListenerFactory',
                'Oauthreg\Authentication\Adapter\ClientIdAuthentication'            => 'Oauthreg\Authentication\Factory\ClientIdAuthenticationFactory',
                
                'Oauthreg\Authentication\Listener\CheckValidationListener'          => 'Oauthreg\Authentication\Factory\CheckValidationListenerFactory',
                'Oauthreg\Authentication\Adapter\CheckValidation'                   => 'Oauthreg\Authentication\Factory\CheckValidationFactory',                
                
//                 'Oauthreg\Authentication\Listener\CheckAuthorizationListener'       => 'Oauthreg\Authentication\Factory\CheckAuthorizationListenerFactory',
//                 'Oauthreg\Authentication\Adapter\CheckAuthorization'                => 'Oauthreg\Authentication\Factory\CheckAuthorizationFactory',
                
                
//                 'ZF\MvcAuth\Authorization\AuthorizationInterface' => 'Oauthreg\Rbac\Authorization',
                'Oauthreg\Rbac\IdentityProvider'        =>  'Oauthreg\Rbac\IdentityProviderFactory',
//                 'Oauthreg\Rbac\AuthenticationListener'  =>  'Oauthreg\Rbac\AuthenticationListenerFactory',
//                 'Oauthreg\Rbac\Authorization'           =>  'Oauthreg\Rbac\AuthorizationFactory',
                
            ),
        );
    }
    
    public function onBootstrap(MvcEvent $event)
    {
    
        $app = $event->getApplication();
        $sm  = $app->getServiceManager();
        $em  = $app->getEventManager();
        $sem  = $em->getSharedManager();
        
        $request = $sm->get('request');
        $router = $sm->get('Router')->match($request);
        
        
//         $request    = $event->getRequest();
//         $routeMatch = $router->getMatchedRouteName();
        
//         echo "router: ";
//         print_r($router);
        
//         echo "routeMatch: ";
//         print_r($routeMatch);
        
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($em);
        
        
        $options        = $sm->get('Oauthreg\Service\RegisterOptions');
        
    
//         $authorizationListener      = $sm->get('Oauthreg\Authentication\Listener\CheckAuthorizationListener');
        $clientIdListener           = $sm->get('Oauthreg\Authentication\Listener\ClientIdAuthenticationListener');
        $checkValidationListener    = $sm->get('Oauthreg\Authentication\Listener\CheckValidationListener');
//         $authorizationListenerRbac  = $sm->get('Oauthreg\Rbac\AuthenticationListener');
               
        
        
        
        
        
        /**
         * XapiKey: for sign the header as Amazon apikey
         */
        if($options->getIsEnableXapikeyHeader())
        {
            if(!$this->isAllwaysAuthorizedRoute($event, $options))
            {
                $em->attach(
                    MvcAuthEvent::EVENT_AUTHORIZATION,
                    $clientIdListener,
                    101
                );
            }
        }
        
        /*****************************/
        /**
         * To force https on all calls.
         */
//         $em->attach('loadModules.post', array($this, 'modulesLoaded'));
        
//         $sharedEvents = $moduleManager->events()->getSharedCollections();
        $sem->attach('Zend\Mvc\Application', 'route', array($this, 'checkHTTPS'), -10);
        
        
        /*****************************/
        //Zend\Permissions\Acl
//         $em->attach(MvcAuthEvent::EVENT_AUTHORIZATION,$authorizationListener,102);
     
//         $em->attach(MvcAuthEvent::EVENT_AUTHORIZATION, $authorizationListenerRbac, 100);
//         $em->attach(MvcAuthEvent::EVENT_AUTHENTICATION_POST, $authorizationListenerRbac, 102);
        
        
//         $em->attach(MvcAuthEvent::EVENT_AUTHENTICATION_POST, array($authorizationListenerRbac, 'accept'), 103);
        
        
//         $sem->attach(
//             'Zend\View\Helper\Navigation\AbstractHelper',
//             'isAllowed',
//             array($authorizationListenerRbac, 'accept')
//         );
        
        
        /*****************************/

        
        if($router!==null)
        {
            $router = $router->getMatchedRouteName();
            if($router == 'oauth')
                $em->attach(MvcAuthEvent::EVENT_AUTHENTICATION, $checkValidationListener, 103);
        }
            
        
//         $em->attach('render', array($this, 'setLayoutTitle'));
        
            
        $em->attach(MvcEvent::EVENT_FINISH, array($this, 'logger'), -1000);
        
//          $em ->attach(MvcEvent:: EVENT_ROUTE, array (
//          $this ,
//          'onRoute'
//          ), - 100);
         
        
    }
    
    
    
    public function logger(MvcEvent $event)
    {
    
//         EMERG   = 0;  // Emergency: system is unusable
//         ALERT   = 1;  // Alert: action must be taken immediately
//         CRIT    = 2;  // Critical: critical conditions
//         ERR     = 3;  // Error: error conditions
//         WARN    = 4;  // Warning: warning conditions
//         NOTICE  = 5;  // Notice: normal but significant condition
//         INFO    = 6;  // Informational: informational messages
//         DEBUG   = 7;  // Debug: debug messages
        
        
        $app = $event->getApplication();
        $sm  = $app->getServiceManager();
        $em  = $app->getEventManager();
        
//         $app          = $e->getTarget();
//         $locator      = $app->getServiceManager();
//         $view         = $locator->get('Zend\View\View');
//         $jsonStrategy = $locator->get('ViewJsonStrategy');
        
        
        $adapterMaster 	= $sm->get('dbMasterAdapter');
        
        
        
        
        
        if($event->getRequest() instanceof ConsoleRequest )  
        {
            // do something important for Console
        } 
        elseif ($event->getRequest() instanceof HttpRequest)
        {
            // do something important for Http
        
            $request  = $event->getRequest();
            $response = $event->getResponse();
            $result   = $event->getResult();
            $remote = new RemoteAddress();
            
            $headers=[];
                foreach ($request->getHeaders()->toArray() as $key => $header) 
                {
                    $headers[] = $key.':'.$header;
                }
                $headersres = [];
//                 foreach ($response->getHeaders()->toArray() as $key => $header)
                {
                    if(is_array($header))
                        $header=serialize($header);
                    $headersres[] = $key.':'.$header;
                }
            
            if($_SERVER['APPLICATION_ENV'] =='development')
            {
                
                    $paypalLog = array(
                        'endpoint' => 'endpoint',
                        'request' => 'request',
                        'header'  => 'header',
                        'rescode'  => 'rescode',
                        'headerres'   => 'headerres',
                        'response' => 'response',
                        'ip' => 'ip',                
                    );
                    
                    // Remove credti card from apilog
                    $string = (strlen($request->getContent())<60000)?$request->getContent():substr($request->getContent(), 0, 1000);
                    
                    
                    $pattern = '/"pan"\s?:\s?"\d{13,16}",/';
                    $replacement = '"pan" : "xxxx"';
                    $myRequest = preg_replace($pattern, $replacement, $string);
                    
                    
//                     $findme = '"pan"';
//                     $pos = strpos($myRequest, $findme);
//                     if ($pos !== false) {
//                          echo "The string '$findme' was found in the string ";
//                              echo " and exists at position $pos";
//                     } else {
//                          echo "The string '$findme' was not found in the string ";
//                     }
                    
                    $data = array(                
                        'endpoint'  => $request->getMethod().' '.$request->getUriString(),
                        'header'    => $headers,
                        'request'   => $myRequest,                
                        'rescode'   => $response->getStatusCode(),
                        'headerres' => $headersres,
                        'response'  => (strlen($response->getBody())<60000)?$response->getBody():substr($response->getBody(), 0, 1000),
                        'ip'        => $remote->getIpAddress(),                
                    );
                    
                    $mapping = array(
                        'priority'  => 'priority',
                        'message'   => 'info',
                        'extra' => $paypalLog                
                    );
                    
                    $mylog = array(
                        'message' => $request->getUri()->getPath(),
                        'extra' => $data);
                    
                    $writer = new logDb($adapterMaster, 'apilog', $mapping);
                    $logger = new Logger();
                    $logger->addWriter($writer);
                    $logger->debug($mylog['message'], $mylog['extra']);                  
                        
            }
        }
        
    }
    
    /**
     * Borrowed from ZfcUser
     * @TODO: Come up with a better way of handling module settings/options
     */
//     public static function getOption($option)
//     {
//         if (!isset(static::$options[$option])) {
//             return null;
//         }
//         return static::$options[$option];
//     }
    
//     public function modulesLoaded($e)
//     {
//         $config = $e->getConfigListener()->getMergedConfig();
//         static::$options = $config['danforcessl'];
//     }
    
    public function checkHTTPS(MvcEvent $e)
    {
        $app = $e->getApplication();
        $sm  = $app->getServiceManager();
        $options        = $sm->get('Oauthreg\Service\RegisterOptions');
        $sslModules = $options->getForceSslModules();
        
        $fqController = $e->getRouteMatch()->getParam("controller");
        $explode = explode("\\", $fqController); // Yes, need new name :D
        $namespace = $explode[0];
        $action = $e->getRouteMatch()->getParam("action");
    
//         echo "fqController: ";
//         print_r($fqController);
//         echo "\n";
//         echo "namespace: ";
//         print_r($namespace);
//         echo "\n";
//         echo "\n";
        
        
        
//         print_r($sslModules);
//         echo "\n";
//         echo "\n";
        
        if(in_array($namespace, $sslModules))
        {
            $request  = $e->getRequest();
            $uri = $request->getUri();
            
//             print_r($uri->getScheme());
//             echo "\n";
//             echo "\n";
            
            
            if($uri->getScheme() !== "https")
            {
                return new ApiProblemResponse(
                    new ApiProblem(
                        505 ,
                        'Supported protocols: https',
                        'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-505' ,
                        'HTTP Version Not Supported'
                    )
                );
            }
            
            // TO FORCE HTTPS
            if($uri->getScheme() !== "https")
            {
//                 $uri->setScheme("https");
//                 $response = $e->getResponse();
//                 $response->getHeaders()->addHeaderLine('Location', $request->getUri());
//                 print_r($response); 
//                 return $response; 
            }
        }
    
    }
    
    
    protected function isAllwaysAuthorizedRoute(MvcEvent $e, $options)
    {
        $services = $e ->getApplication()->getServiceManager();
        $router = $services->get('router');
        $routeMatch = $router->match($services->get('request'));

        if($routeMatch)
        {
            if(strpos($routeMatch->getMatchedRouteName(), '.rest')===false)
            {
                return true;
            }
            if(in_array($routeMatch->getMatchedRouteName(), $options->getAllwaysAuthorizedRoutes()))
                return true;
            else
                return false;
        }
        else
            return true;
        
        
    }
    
    
    
}
