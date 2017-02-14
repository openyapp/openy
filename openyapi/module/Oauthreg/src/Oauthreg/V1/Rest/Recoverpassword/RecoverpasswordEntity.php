<?php
namespace Oauthreg\V1\Rest\Recoverpassword;

class RecoverpasswordEntity
{
    public $email;
    public $first_name;
    public $last_name;
    public $token;
    public $type;
    
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
}
