<?php
namespace Oauthreg\V1\Rpc\VerifySmsNewPhone;

class VerifySmsNewPhoneControllerFactory
{
    public function __invoke($controllers)
    {
        $services = $controllers->getServiceLocator();
        $options = $services->get('Oauthreg\Service\RegisterOptions');
        $dbmaster = $services->get('dbMasterAdapter');
        $dbslave = $services->get('dbSlaveAdapter');
        return new VerifySmsNewPhoneController($services->get('Oauthreg\V1\Rest\Register\RegisterMapper'), $dbmaster, $dbslave, $options);
    }
}
