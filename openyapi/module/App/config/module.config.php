<?php

return array(    
    'controllers' => array(
        'invokables' => array(
            'App\Controller\Index'  => 'App\Controller\IndexController',
            'App\Controller\App'    => 'App\Controller\AppController',
        ),
       
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/app'           => __DIR__ . '/../view/layout/app.phtml',
            'layout/iframe'           => __DIR__ . '/../view/layout/iframe.phtml',
    
        ),
        'template_path_stack' => array(
            'app' => __DIR__ . '/../view',
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
        'App\Controller\IndexController' => 'layout/app',
        'App\Controller\AppController' => 'layout/iframe',
    ),
    
    'navigation' => array(
//         'default' => include('menu.config.php'),
        'userauth' => include('menu.user.config.php'),
    ),
    
    
    
    'router' => array(
        'routes' => array(
            'app' => array(
                'type'    => 'Segment',
                'options' => array(
                            'route'    => '/app/[:action]',
                            'constraints' => array(
                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'App\Controller',
                                'controller'    => 'Index',
                                'action'     => 'index',
                            ),
                        ),
                
            ),
            'appframe' => array(
                'type'    => 'Segment',
                'options' => array(
                            'route'    => '/appframe/[:action]',
                            'constraints' => array(
                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'App\Controller',
                                'controller'    => 'App',
                                'action'     => 'index',
                            ),
                        ),
                
            ),
        ),
    ),
    
);
