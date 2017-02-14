<?php
namespace Opypos\V1\Rpc\MonitorHangPump;

class MonitorHangPumpControllerFactory
{
    public function __invoke($controllers)
    {
        $services = $controllers->getServiceLocator();
        $options  = $services->get('Opypos\Service\OpyposOptions');
        $options2 = $services->get('Openy\Service\OpenyOptions');
        $stationService = $services->get('Opypos\Service\RefuelService');
        $apicaller      = $services->get('ElemApicaller\Service');
        $adapterSlave 	= $services->get('dbSlaveAdapter');
        $collectMapper 	= $services->get('Openy\V1\Rest\Collect\CollectMapper');
        
        return new MonitorHangPumpController($options, $options2, $stationService, $apicaller, $adapterSlave, $collectMapper);
    }
}
