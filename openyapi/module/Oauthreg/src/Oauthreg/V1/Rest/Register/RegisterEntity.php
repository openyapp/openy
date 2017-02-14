<?php
namespace Oauthreg\V1\Rest\Register;

class RegisterEntity
{
    public $email;
    private $password;
    public $first_name;
    public $last_name;
    public $phone_number;
    public $token;
    public $iduser;
    protected $code;
    protected $counter;

    
    
    /**
     * @return the $code
     */
    public function getCode()
    {
        return $this->code;
    }

	/**
     * @param field_type $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

	/**
     * @return the $counter
     */
    public function getCounter()
    {
        return $this->counter;
    }

	/**
     * @param field_type $counter
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;
    }

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
