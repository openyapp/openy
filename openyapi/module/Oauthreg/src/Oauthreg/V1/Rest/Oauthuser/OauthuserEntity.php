<?php
namespace Oauthreg\V1\Rest\Oauthuser;

use Zend\Crypt\Password\Bcrypt;

class OauthuserEntity
{
    public $iduser;
    public $username;
    private $password;
    public $first_name;
    public $last_name;
    public $phone_number;
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function populate($data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
    
    public function verifyPassword($password)
    {
        $bcrypt = new Bcrypt;
        if($bcrypt->verify($password, $this->password))
            return true;
        else
            return false;
    }
}
