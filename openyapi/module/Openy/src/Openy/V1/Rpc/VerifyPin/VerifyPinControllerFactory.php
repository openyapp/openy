<?php
namespace Openy\V1\Rpc\VerifyPin;

class VerifyPinControllerFactory
{
    public function __invoke($controllers)
    {
        $services = $controllers->getServiceLocator();
        $options  = $services->get('Openy\Service\OpenyOptions');
        $dbmaster = $services->get('dbMasterAdapter');
        $dbslave  = $services->get('dbSlaveAdapter');
        return new VerifyPinController($services->get('Openy\V1\Rest\Preference\PreferenceMapper'), $dbmaster, $dbslave, $options);
    }
}
