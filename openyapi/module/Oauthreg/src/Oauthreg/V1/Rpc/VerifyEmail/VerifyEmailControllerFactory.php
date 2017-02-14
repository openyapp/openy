<?php
namespace Oauthreg\V1\Rpc\VerifyEmail;

class VerifyEmailControllerFactory
{
    public function __invoke($controllers)
    {
        $services = $controllers->getServiceLocator();
        $options = $services->get('Oauthreg\Service\RegisterOptions');
        return new VerifyEmailController($services->get('Oauthreg\V1\Rest\Register\RegisterMapper'), $options);
    }
}
