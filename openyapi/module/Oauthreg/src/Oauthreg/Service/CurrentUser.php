<?php
namespace Oauthreg\Service;

class CurrentUser
{
    protected $request;
    protected $repository;
    protected $user = null;
    protected $bearer = null;

    public function __construct($request, $repository)
    {
        $this->request = $request;
        $this->repository = $repository;
    }

    
    
    /**
     * @param field_type $bearer
     */
    public function setBearer($bearer)
    {
        $this->bearer = $bearer;
        return $this;
    }

	public function getBearer()
    {
        if($this->bearer != null)
            return $this->bearer;
        else 
        {
            $authentication = $this->request->getHeaders()->get('Authorization')->getFieldValue();
            if(!$authentication)
                $this->bearer = false;
            else    
                $this->bearer = $this->extractPublicKey($authentication);
            
            return $this->bearer;
        }
        
    }

    public function getClient()
    {
        $publicKey = $this->getBearer();
        return $this->repository->getClientByBearer($publicKey);
    }

    
    /**
     * @return the $user
     */
    public function getUser($key = null)
    {
        if($this->user != null)
        {
            if($key)
                return $this->user[$key];
            else
                return $this->user;
        }
        else 
        {
            $user = $this->getUserFromBearer();
            $this->setUser($user);
            if($key)
                return $this->user[$key];
            else
                return $this->user;
        }
    }

	/**
     * @param field_type $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    
    
	public function getUserFromBearer()
    {
        $request = $this->request;
        $headers = $request->getHeaders();

        if ($headers->has('Authorization'))
        {
            $publicKey = $this->getBearer();

            if($publicKey)
                $user = $this->repository->getUserByBearer($publicKey);
            
            
            if($user)
                $user = $this->repository->getIdUser($user['user_id']);
            // For user_id only
            // See at https://groups.google.com/a/zend.com/forum/#!msg/apigility-users/t1CbVFIYxZE/QmvcJhM71ugJ
            //print_r($this->getServiceLocator()->get('api-identity'));

            
            return $user;
        }
        return false;

    }

    public function get($key = null)
    {
        $request = $this->request;
        $headers = $request->getHeaders();

        if ($headers->has('Authorization'))
        {
            $publicKey = $this->getBearer();

            if($publicKey)
                $user = $this->repository->getUserByBearer($publicKey);

            if($user)
                $user = $this->repository->getIdUser($user['user_id']);

            if($key)
                return $user[$key];
            else
                return $user;
        }
        return false;

    }

    protected function extractPublicKey($authentication)
    {
        $authentication = explode('Bearer ',$authentication);

        if(isset($authentication[1]))
            return $authentication[1];
        else
            return false;
    }
}
