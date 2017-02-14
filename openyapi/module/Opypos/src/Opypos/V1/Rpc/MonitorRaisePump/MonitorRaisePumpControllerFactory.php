<?php
namespace Opypos\V1\Rpc\MonitorRaisePump;

class MonitorRaisePumpControllerFactory
{
    public function __invoke($controllers)
    {
        $services = $controllers->getServiceLocator();
        $options  = $services->get('Opypos\Service\OpyposOptions');
        $options2 = $services->get('Openy\Service\OpenyOptions');
        $stationService = $services->get('Opypos\Service\RefuelService');
        $apicaller      = $services->get('ElemApicaller\Service');
        $adapterSlave 	= $services->get('dbSlaveAdapter');
        return new MonitorRaisePumpController($options, $options2, $stationService, $apicaller, $adapterSlave);
    }
}
