<?php
namespace Oauthreg\V1\Rpc\Revoke;

class RevokeControllerFactory
{
    public function __invoke($controllers)
    {
        $services       = $controllers->getServiceLocator();
        $options        = $services->get('Oauthreg\Service\RegisterOptions');
        $currentUser    = $services->get('Oauthreg\Service\CurrentUser');
        return new RevokeController($services->get('Oauthreg\V1\Rest\Recoverpassword\RecoverpasswordMapper'), $options, $currentUser);
    }
}
