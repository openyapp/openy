<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
		'asset_manager' => array(
				'resolver_configs' => array(
						'paths' => array(
								__DIR__ . '/../public',
						),
				),
		),
    'controllers' => array(
        'invokables' => array(
            'Developer\Controller\Index' => 'Developer\Controller\IndexController',
        	'Developer\Controller\Debug' => 'Developer\Controller\DebugController',
            'Developer\Controller\Markdown' => 'Developer\Controller\MarkdownController',
            'Developer\Controller\Talk' => 'Developer\Controller\TalkController',
            'Developer\Controller\PHPDoc' => 'Developer\Controller\PHPDocController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'developer' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/developer[/:action]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Developer\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'debug' => array(
            		'type'    => 'Segment',
            						'options' => array(
            								'route'    => '/debug[/:action]',
            								'constraints' => array(
            										'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
            										'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
            								),
            								'defaults' => array(
				            						'controller' => 'Developer\Controller\Debug',
				            						'action'     => 'index',
				            				),
            						),
            ),

            'markdown' => array(
            		'type'    => 'segment',
            		'options' => array(
            				'route'    => '/docs[[/:filename][/:namespace]]',
            				'defaults' => array(
            						'controller' => 'Developer\Controller\Markdown',
            						'action'     => 'index',
            				),
            		),
            ),
        ),
    ),
    'view_manager' => array(
            'template_path_stack' => array(
                    'developer' => __DIR__ . '/../view',
            ),

    ),

    'navigation' => array(
//         'default' => include('menu.config.php'),
        'userauth' => include('menu.user.config.php'),
    ),

);
