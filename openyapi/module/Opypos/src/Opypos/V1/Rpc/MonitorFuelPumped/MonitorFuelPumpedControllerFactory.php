<?php
namespace Opypos\V1\Rpc\MonitorFuelPumped;

class MonitorFuelPumpedControllerFactory
{
    public function __invoke($controllers)
    {
        $services = $controllers->getServiceLocator();
        $options  = $services->get('Opypos\Service\OpyposOptions');
        $options2 = $services->get('Openy\Service\OpenyOptions');
        $stationService = $services->get('Opypos\Service\RefuelService');
        return new MonitorFuelPumpedController($options, $options2, $stationService);
    }
}
