<?php
namespace Opypos\V1\Rpc\Ping;

class PingControllerFactory
{
   
    public function __invoke($controllers)
    {
        $services = $controllers->getServiceLocator();
        $options        = $services->get('Opypos\Service\OpyposOptions');
        $apicaller      = $services->get('ElemApicaller\Service');
        $dbslave        = $services->get('dbSlaveAdapter');
        return new PingController($dbslave, $options, $apicaller);
    }
}
