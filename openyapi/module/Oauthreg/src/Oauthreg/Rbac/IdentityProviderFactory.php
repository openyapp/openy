<?php
namespace Oauthreg\Rbac;

use \Zend\ServiceManager\ServiceManager;
use Oauthreg\Rbac\IdentityProvider;

class IdentityProviderFactory
{
    public function __invoke(ServiceManager $services)
    {
        /** @var \Zend\Authentication\AuthenticationService $authenticationProvider */
        $authenticationProvider = $services->get('authentication');

        $identityProvider = new IdentityProvider();
        $identityProvider->setAuthenticationProvider($authenticationProvider);
        return $identityProvider;
    }
}