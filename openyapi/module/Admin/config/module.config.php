<?php

return array(    
    'controllers' => array(
        'factories' => array(
            'Admin\Controller\Console'  => 'Admin\Controller\ConsoleControllerFactory',
        ),
        'invokables' => array(
            'Admin\Controller\Index'    => 'Admin\Controller\IndexController',
            'Admin\Controller\Admin'    => 'Admin\Controller\AdminController',
            'Admin\Controller\Monitor'  => 'Admin\Controller\MonitorController',
            'Admin\Controller\Chat'  => 'Admin\Controller\ChatController'
        ),
       
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/bootstrap-theme.phtml',
            'layout/monitor'           => __DIR__ . '/../view/layout/monitor.phtml',
            'admin/layout'            => __DIR__ . '/../view/layout/jumbotron-narrow.phtml',
            'admin/index/index'       => __DIR__ . '/../view/admin/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/500'               => __DIR__ . '/../view/error/500.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'admin' => __DIR__ . '/../view',
        ),
    ),
    
    'asset_manager' => array(
        'resolver_configs' => array(
        				'paths' => array(
        				    __DIR__ . '/../public',
        				),
        ),
    ),
    
//     'module_layouts' => array(
//         'Admin' => 'layout/monitor',
//     ),
    'controller_layouts' => array(
        'Admin\Controller\MonitorController' => 'layout/monitor',
    ),
    
    'navigation' => array(
        'default' => include('menu.config.php'),
        'userauth' => include('menu.user.config.php'),
    ),
    
    'console'         => array(
        'router' => array(
            'routes' => array(
                'import-gasstation' => array(
                    'options' => array(
                        'route'    => 'import gasstation <filename> [--verbose|-v]',
                        'defaults' => array(
                            'controller' => 'Admin\Controller\Console',
                            'action'     => 'import',
                        ),
                    ),
                ),
                'import-address' => array(
                    'options' => array(
                        'route'    => 'import address',
                        'defaults' => array(
                            'controller' => 'Admin\Controller\Console',
                            'action'     => 'address',
                        ),
                    ),
                ),
                'import-locality' => array(
                    'options' => array(
                        'route'    => 'import locality',
                        'defaults' => array(
                            'controller' => 'Admin\Controller\Console',
                            'action'     => 'locality',
                        ),
                    ),
                ),
                'import-municipality' => array(
                    'options' => array(
                        'route'    => 'import municipality',
                        'defaults' => array(
                            'controller' => 'Admin\Controller\Console',
                            'action'     => 'municipality',
                        ),
                    ),
                ),
                'getminetur-type' => array(
                    'options' => array(
                        'route'    => 'getminetur <type> [--verbose|-v]',
                        'defaults' => array(
                            'controller' => 'Admin\Controller\Console',
                            'action'     => 'getminetur',
                        ),
                    ),
                ),
//                 'monitor-raise' => array(
//                     'options' => array(
//                         'route'    => 'monitor raise',
//                         'defaults' => array(
//                             'controller' => 'Admin\Controller\Console',
//                             'action'     => 'monitorRaise',
//                         ),
//                     ),
//                 ),
            ),
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action

            'admin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/admin[/:action][/:file]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Admin',
                        'action'     => 'index',
                    ),
                )
            ),
            'monitor' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/admin/monitor[/:action][/:file]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Monitor',
                        'action'     => 'index',
                    ),
                )
            ),
            'chat' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/admin/chat[/:action][/:file]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Chat',
                        'action'     => 'index',
                    ),
                )
            ),
        ),
    ),
    
    
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories' => array(
            // Menus
            'navigation' => 'Admin\Navigation\Service\UserauthNavigationFactory',
            'default' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'userauth' => 'Admin\Navigation\Service\UserauthNavigationFactory',
//             'Zend\Authentication\AuthenticationService' => 'Zend\Authentication\AuthenticationService',
        ),
    ),
    
    
    
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    
   
    
   
);
