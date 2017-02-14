<?php
namespace Opypos;

use ZF\Apigility\Provider\ApigilityProviderInterface;

use Opypos\V1\Rest\Configuration\ConfigurationMapper;
use Opypos\V1\Rest\Price\PriceMapper;
use Opypos\V1\Rest\Pump\PumpMapper;
use Opypos\V1\Rest\Closest\ClosestMapper;
use Opypos\V1\Rest\Pumpstatus\PumpstatusMapper;
use Opypos\V1\Rest\Station\StationMapper;

// use Zend\Stdlib\Hydrator\ObjectProperty;

class Module implements ApigilityProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

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
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Opypos\Service\OpyposOptions' => function ($sm) {
                    $config = $sm->get('Config');
                    return new \Opypos\Service\OpyposOptions(isset($config['opypos']) ? $config['opypos'] : array());
                },
                'Opypos\Service\RefuelService' => function ($sm) {
                    $options        = $sm->get('Opypos\Service\OpyposOptions');
                    $apicaller      = $sm->get('ElemApicaller\Service');
                    $currentUser    = $sm->get('Oauthreg\Service\CurrentUser');
                    $currentPreferences = $sm->get('Openy\Service\CurrentPreferences');
                    $orderService   = $sm->get('Openy\Service\Order');
                    return new \Opypos\Service\RefuelService($options, $apicaller, $currentUser, $currentPreferences, $orderService);
                },
                'Opypos\V1\Rest\Configuration\ConfigurationMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Opypos\Service\OpyposOptions');
                    $apicaller      = $sm->get('ElemApicaller\Service');
                    return new ConfigurationMapper($adapterMaster, $adapterSlave, $options, $apicaller);
                },
                'Opypos\V1\Rest\Price\PriceMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Opypos\Service\OpyposOptions');
                    $apicaller      = $sm->get('ElemApicaller\Service');
                    return new PriceMapper($adapterMaster, $adapterSlave, $options, $apicaller);
                },
                'Opypos\V1\Rest\Pumpstatus\PumpstatusMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Opypos\Service\OpyposOptions');
                    $apicaller      = $sm->get('ElemApicaller\Service');
                    return new PumpstatusMapper($adapterMaster, $adapterSlave, $options, $apicaller);
                },
                'Opypos\V1\Rest\Pump\PumpMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Opypos\Service\OpyposOptions');
                    $apicaller      = $sm->get('ElemApicaller\Service');
                    $priceMapper    = $sm->get('Opypos\V1\Rest\Price\PriceMapper');
                    return new PumpMapper($adapterMaster, $adapterSlave, $options, $apicaller, $priceMapper);
                },
                'Opypos\V1\Rest\Closest\ClosestMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Opypos\Service\OpyposOptions');
                    $apicaller      = $sm->get('ElemApicaller\Service');
                    return new ClosestMapper($adapterMaster, $adapterSlave, $options, $apicaller);
                },
                'Opypos\V1\Rest\Station\StationMapper' =>  function ($sm) {
                    $adapterMaster 	= $sm->get('dbMasterAdapter');
                    $adapterSlave 	= $sm->get('dbSlaveAdapter');
                    $options        = $sm->get('Opypos\Service\OpyposOptions');
                    return new StationMapper($adapterMaster, $adapterSlave, $options);
                },
                
                
                /*
                'Opypos\V1\Rest\Configuration\ConfigurationMapper2' => function($sm) {
                    $options = $sm->get('Opypos\Service\OpyposOptions');
                    $entityClass = 'Opypos\V1\Rest\Configuration\ConfigurationEntity';
                    $collectionClass = 'Opypos\V1\Rest\Configuration\ConfigurationCollection';
                    $mapper = new \Opypos\V1\Rest\Configuration\ConfigurationMapperSp();
                    $mapper->setApiCaller($sm->get('ElemApicaller\Service'));
                    $mapper->setHydrator(new ObjectProperty());
                    $mapper->setEntityPrototype(new $entityClass);
                    $mapper->setCollectionPrototype($collectionClass);
                    
                    $network = $options->getPosNetwork();
                    $url = $network['opy_1']['endpoint']."/";
                    
                    
                    $mapper->setUrl($url);
                    $mapper->setResource(array('configuration'));
                    return $mapper;
                },
                */
                
                'Opypos\Adapter\aadapterAdapter' => 'Opypos\Adapter\aadapterAdapterFactory',
                
            ),
            'aliases' => array(
                'aadapter' => 'Opypos\Adapter\aadapterAdapter',
            ),
        );
    }
}
