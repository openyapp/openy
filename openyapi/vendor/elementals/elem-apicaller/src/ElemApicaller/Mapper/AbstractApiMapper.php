<?php

namespace ElemApicaller\Mapper;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Http\Request;

use ElemApicaller\Mapper\ApiMapperInterface;
use ElemApicaller\Service\ApiCaller;
use ElemValidators\Validator\Interfaces\Record;
use Zend\Json\Encoder as JsonEncoder;

use Zend\Paginator\Adapter\Iterator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;

abstract class AbstractApiMapper implements ApiMapperInterface
{     
  
//     protected $options; 
    
    protected $apiCaller;
        
    /**
     * @var HydratorInterface
     */
    protected $hydrator;
    
    /**
     * @var object
     */
    protected $entityPrototype;
    
    /**
     * @var HydratingCollectionSet
     */
    protected $collectionPrototype;
       
    /**
     * @var boolean
     */
    private $isInitialized = false;
    
    /**
     * @var string
     */
    protected $url;
    
    /**
     * @var string
     */
    protected $resource;
    
    

	/**
     * Performs some basic initialization setup and checks before running a query
     * @return null
     */
    protected function initialize()
    {
        if ($this->isInitialized) {
            return;
        }
    
        if (!$this->apiCaller instanceof ApiCaller) {
            throw new Exception\InvalidAdapterException('No apiCaller present');
        }
    
        if (!$this->hydrator instanceof HydratorInterface) {
            throw new Exception\InvalidAdapterException('No hydrator present');
        }
    
//         if (!is_object($this->entityPrototype)) {
//             throw new Exception\InvalidArgumentException('No entity prototype set');
//         }
        
        if (!$this->entityPrototype) {
            throw new Exception\InvalidArgumentException('No entity prototype set');
        }
        
//         if (!is_object($this->collectionPrototype)) {
//             throw new Exception\InvalidArgumentException('No collection prototype set');
//         }
    
        $this->isInitialized = true;
    }
        
    
    /**
     * Fetch a collection
     *
     * @param  mixed $filter
     * @return ApiProblem|mixed
     */
    public function fetchAll(array $filter = null)
    {   
        $this -> initialize();
        $url = sprintf($this->url.$this->resource[0]);

        $client = $this->getApiCaller()->getClientInstance();
        
        if(isset($filter) && !empty($filter))
        {   
            $client -> setParameterGet($filter);
        }
        else
        {
            $client -> resetParameters();
        }
        $data =  $this->getApiCaller()->doRequest($url);
        $collectionName = $this->getCollectionPrototype();
        $collection = new $collectionName($data, $this->getEntityPrototype(), $this->hydrator);
        return $collection;    	
    }
     
    public function findByFilters(array $filters)
    {
        foreach  ($filters as $key => $filter)
        {
            $result = $this->fetchAll(array($key=>$filter));
        }
        return $result;
    }
    
    public function enableFilters($filters)
    {
        foreach ($filters as $filter => $value)
        {
            if(!in_array($filter, $this->getOptions()->getValidFilters()))
            {
                return false;
            }
        }
        return true;
    }
    
   
    public function rpc($url, $data = null)
    {
        $this->initialize();
        if($data == null)
            $result = $this->getApiCaller()->doRequest($this->url.$url, null, Request::METHOD_GET);
        else
            $result = $this->getApiCaller()->doRequest($this->url.$url, $data, Request::METHOD_POST);
    	
    	return $result;
    }
    
    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
 	public function fetch($id)
    {
        $this->initialize();
        $url = sprintf($this->url.$this->resource[0], $id);    	
    	$value = $this->getApiCaller()->doRequest($url, null, Request::METHOD_GET);
		return $value;
    }
     
    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create(array $data)
    {
        $this->initialize();        
    	$url = sprintf($this->url.$this->resource[0]);
    	$value = $this->getApiCaller()->doRequest($url, $data, Request::METHOD_POST);    			
    	return $value;    	
    }
    
    /**
     * Update a resource (replace)
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, array $data)
    {    	
//         $this->initialize();
//         $token = $this->token->getToken();
//         $this->addHeaders(array('Authorization'=> 'Bearer '.$token['access_token']));
//     	$url = sprintf($this->getEndpointEntity(), $id);
//     	$entity = $this->doRequest($url,Json::TYPE_ARRAY,$data,Request::METHOD_PUT);
    	
//     	return $entity;
    }
    
    /**
     * Update some fileds of a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, array $data)
    {
        $this->initialize();        
        $url = sprintf($this->url.$this->resource[0].$this->resource[1], $id);
        $value = $this->getApiCaller()->doRequest($url,$data,Request::METHOD_PATCH);         
        return $value;
    }
	
    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        $this->initialize();
    	$url = sprintf($this->url.$this->resource[0].$this->resource[1], $id);
    	$value = $this->getApiCaller()->doRequest($url,(array) $id,Request::METHOD_DELETE);
    	return $value;
    }
	
    
    /****************************** GETTER & SETTERS *************************************/
	
 
    
    /**
     * @return the $apiCaller
     */
    public function getApiCaller()
    {
        return $this->apiCaller;
    }
    
    /**
     * @param field_type $apiCaller
     */
    public function setApiCaller($apiCaller)
    {
        $this->apiCaller = $apiCaller;
        return $this;
    }
    
	/**
     * @return object
     */
    public function getEntityPrototype()
    {
        return $this->entityPrototype;
    }
    
    /**
     * @param object $modelPrototype
     * @return AbstractDbMapper
     */
    public function setEntityPrototype($entityPrototype)
    {
        $this->entityPrototype = $entityPrototype;
        return $this;
    }
    
    /**
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = new ObjectProperty(false);
        }
        return $this->hydrator;
    }
    
    /**
     * @param HydratorInterface $hydrator
     * @return AbstractDbMapper
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }
    
	/**
     * @return the $collectionPrototype
     */
    public function getCollectionPrototype()
    {
        return $this->collectionPrototype;
    }

	/**
     * @param \ApiCaller\Mapper\HydratingResultSet $collectionPrototype
     */
    public function setCollectionPrototype($collectionPrototype)
    {
        $this->collectionPrototype = $collectionPrototype;
        return $this;
    }
    
	/**
     * @return the $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param field_type $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
    
	/**
     * @return the $resource
     */
    public function getResource()
    {
        return $this->resource;
    }
    
    /**
     * @param field_type $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
        return $this;
    }
    
	/**
     * @return the $options
     */
//     public function getOptions()
//     {
//         return $this->options;
//     }

	/**
     * @param field_type $options
     */
//     public function setOptions($options)
//     {
//         $this->options = $options;
//     }


   

	

    
    
	
}
