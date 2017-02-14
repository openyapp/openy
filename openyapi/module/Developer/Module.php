<?php
/*
 * Read order of config functions and variables
 *
 * 	getConfig()
 *  getServiceConfig() translates to $config['service_manager']
 * 	getControllerConfig() translates to $config['controllers']
 * 	getControllerPluginConfig() translates to $config['controller_plugins']
 * 	getViewHelperConfig() translates to $config['view_helpers']
 * 	getValidatorConfig() translates to $config['validators']
 * 	getFilterConfig() translates to $config['filters']
 * 	getFormElementConfig() translates to $config['form_elements']
 * 	getRouteConfig() translates to $config['route_manager']
 * 	alphabetically config/autoload/{,*.}{global,local}.php.
 *
 */

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Developer;

// use Zend\Mvc\ModuleRouteListener;
// use Zend\Mvc\MvcEvent;
use Michelf\MarkdownExtra;
// use Zend\EventManager\EventInterface;
// use Zend\Mvc\MvcEvent;

class Module extends \Zend\View\Helper\AbstractHelper
{   

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
    	return array('services' => array('markdown' => $this));
    }
    
	public function __invoke($string = null)
    {
        return MarkdownExtra::defaultTransform($string);
    }
    
    
    public function getServiceConfig() {
    	return array(
    			'factories' => array(
    					'Zend\Log\FirePhp' => function($sm) {
    						$writer_firebug = new \Zend\Log\Writer\FirePhp();
    						$logger = new \Zend\Log\Logger();
    						$logger->addWriter($writer_firebug);
    						return $logger;
    					},
    			),
    	);
    }
}
