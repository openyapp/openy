<?php
namespace Oauthreg\Rbac;

use ZfcRbac\Identity\IdentityProviderInterface;
use Zend\Authentication\AuthenticationService;
use Oauthreg\Rbac\Identity;
use ZF\MvcAuth\Identity\AuthenticatedIdentity;

/**
 * Class IdentityProvider provides Identity object required by RBAC.
 * We return custom Identity because we connect OAuth2 authentication (returning userId) and RBAC authorization (requiring roles)
 *
 * @package YourApp\Rbac
 */
class IdentityProvider implements IdentityProviderInterface
{
    /** @var Identity $rbacIdentity */
    private $rbacIdentity = null;

    /* @var \Zend\Authentication\AuthenticationService $authenticationProvider */
    private $authenticationProvider;

    public function setAuthenticationProvider(AuthenticationService $authenticationProvider)
    {
        $this->authenticationProvider = $authenticationProvider;
        return $this;
    }

    /**
     * Checks if user is authenticated. If yes, checks db for user's role and returns Identity.
     *
     * @return Identity
     */
    public function getIdentity()
    {
        if ($this->rbacIdentity === null)
        {
            $this->rbacIdentity = new Identity();

            $mvcIdentity = $this->authenticationProvider->getIdentity();
//             var_dump($mvcIdentity);
            if($mvcIdentity instanceof AuthenticatedIdentity)
            {
                $role = $mvcIdentity->getRoleId();
                            
//                             echo "Role: ";
//                             print_r($role);
//                             echo "\n"."---"."\n";
                            
//                             echo "Children: ";
//                             var_dump(get_class($this->rbacIdentity));
//                             echo "\n"."---"."\n";
                            
//                             echo "getParent: ";
//                             print_r($mvcIdentity->getParent());
//                             echo "\n"."---"."\n";
//                             $role='guest';
                            
//                             echo "rbacIdentity: ";
//                             print_r($this->rbacIdentity);
//                             echo "\n"."---"."\n";
                            
//                             echo "IdentityProvider: ";
//                             print_r("IdentityProvider");
//                             echo "\n"."---"."\n";
//                             echo "---"."\n";
                            
                $this->rbacIdentity
                     ->setRoles($role);
                
                //             print_r($this->rbacIdentity->getRoles());
                
            }
            
            
        }
        
        return $this->rbacIdentity;
        
    }
}