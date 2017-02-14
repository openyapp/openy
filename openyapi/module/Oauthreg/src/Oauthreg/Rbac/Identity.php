<?php
namespace Oauthreg\Rbac;

use ZfcRbac\Identity\IdentityInterface;

class Identity implements IdentityInterface
{
    private $roles = array();

    public function setRoles($roles)
    {
        if (!is_array($roles)) {
            $roles = array($roles);
        }
        $this->roles = $roles;
        return $this;
    }

    /**
     * Get the list of roles of this identity
     *
     * @return string[]|\Rbac\Role\RoleInterface[]
     */
    public function getRoles()
    {
        return $this->roles;
    }
}