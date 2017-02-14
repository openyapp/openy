<?php
namespace Oauthreg\V1\Rpc\SetNewPassword;

class SetNewPasswordControllerFactory
{
    public function __invoke($controllers)
    {
        $services = $controllers->getServiceLocator();
        return new SetNewPasswordController(
                                            $services->get('dbMasterAdapter'),
                                            $services->get('dbSlaveAdapter'),
                                            $services->get('Oauthreg\V1\Rest\Recoverpassword\RecoverpasswordMapper'),   // oauth_register
                                            $services->get('Oauthreg\V1\Rest\Oauthuser\OauthuserMapper'),               // oauth_users
                                            $services->get('Oauthreg\Service\RegisterOptions')                          // Options
                                            );
    }
}
