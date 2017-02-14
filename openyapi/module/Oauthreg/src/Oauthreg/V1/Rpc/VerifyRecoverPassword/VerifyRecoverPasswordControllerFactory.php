<?php
namespace Oauthreg\V1\Rpc\VerifyRecoverPassword;

class VerifyRecoverPasswordControllerFactory
{
    public function __invoke($controllers)
    {
        $services = $controllers->getServiceLocator();    
        return new VerifyRecoverPasswordController($services->get('Oauthreg\V1\Rest\Recoverpassword\RecoverpasswordMapper'));
    }
}
