<?php
namespace Oauthreg\V1\Rpc\VerifySms;

class VerifySmsControllerFactory
{
    public function __invoke($controllers)
    {
        $services = $controllers->getServiceLocator();
        $options = $services->get('Oauthreg\Service\RegisterOptions');
        return new VerifySmsController($services->get('Oauthreg\V1\Rest\Register\RegisterMapper'), $options);
    }
}
