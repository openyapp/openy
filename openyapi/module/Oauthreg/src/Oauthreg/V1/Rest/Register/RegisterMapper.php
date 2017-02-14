<?php 
namespace Oauthreg\V1\Rest\Register;

use DomainException;
use InvalidArgumentException;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Insert;
 
use Zend\Db\Sql\Delete;
use Zend\Db\Adapter\AdapterInterface; 
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\AbstractOptions;

// use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\Reflection;
use Zend\Http\PhpEnvironment\RemoteAddress;

use Rhumsaa\Uuid\Uuid;
use Zend\Crypt\Password\Bcrypt;
use Zend\Http\Request;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class RegisterMapper 
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;      
    private $tableName = 'oauth_register';
    protected $mailer;
    protected $apicaller;
    
    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options, $mailer, $apicaller)
    {
        $this->adapterMaster = $adapterMaster;
        $this->adapterSlave  = $adapterSlave;
        $this->options		 = $options;    
        $this->mailer        = $mailer;
        $this->apicaller     = $apicaller;
    }
   
    
    public function isRegistered($email)
    {
        $select = new Select('oauth_users');
        $select->where(array('username' => $email));
        //var_dump($select->getSqlString());
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
         
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype(new RegisterEntity);
        $resultSet->initialize($driverResult);
        
        if (0 === count($resultSet)) {
            return false;
        }
        
        return true;
    }
    
    public function isRegisteredByid($iduser)
    {
        $select = new Select('oauth_users');
        $select->where(array('iduser' => $iduser));
        //var_dump($select->getSqlString());
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
         
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype(new RegisterEntity);
        $resultSet->initialize($driverResult);
    
        if (0 === count($resultSet)) {
            return false;
        }
    
        return true;
    }
    
    public function verifyEmail($token, $email)
    {   
        if($this->isRegistered($email))
            return true;
        
        if (empty($token)) {
            throw new DomainException('Invalid token provided', 404);
        }
        
        if (empty($email)) {
            throw new DomainException('Invalid identifier provided', 404);
        }
         
        $select = new Select($this->tableName);
        $select->where(array('token' => $token, 'email' => $email));
         
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
         
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype(new RegisterEntity);
        $resultSet->initialize($driverResult);
        
        if (0 === count($resultSet)) {
            return false;
        }
        
        // Get uer data
        $entity = new RegisterEntity();
        $entity->populate($resultSet->current());
        
        
        if( time() < strtotime($entity->created)+86400)
            return true;
        else
            return false;
        
        
        // Timestamp validation
        // Ok go as now
        // KO
        // delete and return false
        
    }
    
    public function verifyNewSms($code, $iduser)
    {
        if(!$this->isRegisteredByid($iduser))
            return false;
    
        if (empty($code)) {
            throw new DomainException('Invalid code provided', 404);
        }
         
        $select = new Select($this->tableName);
        $select->where(array('code' => $code, 'iduser' => $iduser));
        //var_dump($select->getSqlString());
    
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
         
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype(new RegisterEntity);
        $resultSet->initialize($driverResult);
    
        if (0 === count($resultSet)) {
            return false;
        }
    
        // Get created
        $entity = new RegisterEntity();
        $entity->populate($resultSet->current());
    
    
        if( time() < strtotime($entity->updated)+86400)
            return true;
        else
            return false;
    
    
        // Timestamp validation
        // Ok go as now
        // KO
        // delete and return false
    
    }
    
    public function verifySms($code, $iduser)
    {
        if($this->isRegisteredByid($iduser))
        {
            return false;
            throw new DomainException('User already registered', 404);            
        }
            
    
        if (empty($code)) {
            throw new DomainException('Invalid code provided', 404);
        }
         
        $select = new Select($this->tableName);
        $select->where(array('code' => $code, 'iduser' => $iduser));
        //var_dump($select->getSqlString());
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
         
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype(new RegisterEntity);
        $resultSet->initialize($driverResult);
    
        if (0 === count($resultSet)) {
            return false;
        }
    
        // Get created
        $entity = new RegisterEntity();
        $entity->populate($resultSet->current());
    
        if( time() < strtotime($entity->updated)+86400)
            return true;
        elseif( time() < strtotime($entity->created)+86400)
            return true;
        else
            return false;
    
    
        // Timestamp validation
        // Ok go as now
        // KO
        // delete and return false
    
    }
    
    public function fetchAll($filter)
    {
        $select = new Select($this->tableName);
            
        /**
         * Filters
         */
        isset($filter['email'])?$select->where(array('email' => $filter['email'])):null;
        
        $resultset = new HydratingResultSet;
        $resultset->setObjectPrototype(new RegisterEntity);
    
        $paginatorAdapter = new DbSelect(
            $select,
            $this->adapterSlave,
            $resultset
        );
    
        $collection = new RegisterCollection($paginatorAdapter);
        return $collection;
    }
    
    /**
     * @param string $id
     * @return Entity
     */
    public function fetch($id)
    {
        if (empty($id)) {
    	    throw new DomainException('Invalid identifier provided', 404);
    	}        	
    	
    	$select = new Select($this->tableName);
    	$select->where(array('email' => $id));    	
    	//var_dump($select->getSqlString());
    	
    	$statement = $this->adapterSlave->createStatement();
    	$select->prepareStatement($this->adapterSlave, $statement);
    	$driverResult = $statement->execute();
    	
    	$resultSet = new HydratingResultSet;
    	$resultSet->setObjectPrototype(new RegisterEntity);    	
    	$resultSet->initialize($driverResult); 
    	
    	if (0 === count($resultSet)) {
    		throw new DomainException('Not found', 404);
    	}

    	$entity = new RegisterEntity();
    	$entity->populate($resultSet->current());
    	return $entity;
    }   
    
    public function update($data, $id = null)
    {
        $data = (array)$data;
        
        if (!empty($id)) {
            $data['email'] = $id;
        }
        
        $entity = new RegisterEntity();
//         \Zend\Debug\Debug::dump($entity, "entity before : ");
        
        $hydrator = new Reflection();
        $hydrator->hydrate($data, $entity);
        //$entity->populate($data);
         
//         \Zend\Debug\Debug::dump($entity, "entity after: ");
        
//         \Zend\Debug\Debug::dump($hydrator->extract($entity), "entity extracted: ");
        $data = $hydrator->extract($entity);
        
//         \Zend\Debug\Debug::dump($data, "data: ");
        
        if (isset($data['email'])) {
            // update action
            $action = new Update($this->tableName);
            $action->set($data);
            $action->where(array('email' => $id));
        } 
        //      var_dump($action->getSqlString());
         
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        
        $data['email']= $this->adapterMaster->getDriver()->getLastGeneratedValue();
        
        $hydrator->hydrate($data, $entity);
         
        return $entity;
    }
    
    public function getUserInfo($id)
    {
        $entity = new RegisterEntity();
        
        $select = new Select($this->tableName);
        $select->where(array('email' => $id));
        //var_dump($select->getSqlString());
         
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
         
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype(new RegisterEntity);
        $resultSet->initialize($driverResult);
        
        return $resultSet;
    }
    public function recordRegisterBy($id)
    {
        if($this->isRegistered($id))
            return 'do_nothing';  
        
        $resultSet = $this->getUserInfo($id);
        
        if (0 === count($resultSet)) 
            return 'insert';
        
        $entity = new RegisterEntity();
    	$entity->populate($resultSet->current());
        
    	
    	// Next days no register sms or email sent
    	if( time() < strtotime($entity->updated)+86400)
    	    return 'do_nothing';
    	elseif( time() < strtotime($entity->created)+86400)
    	   return 'do_nothing';
        
        return 'update';      
    }
    
    private function getCounterData($id)
    {
        $select = new Select($this->tableName);
        $select->where(array('email' => $id));
        //var_dump($select->getSqlString());
    
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
    
        $userData = $driverResult->current();
        
        return $userData['counter'];
    }
    
    public function insert($data)
    {
        $data = (array)$data;
        $remote = new RemoteAddress();
    
        $entity = new RegisterEntity();        
        $hydrator = new Reflection();
        $hydrator->hydrate($data, $entity);
        //$entity->populate($data);         
        $data = $hydrator->extract($entity);     
          
        $data['created'] = date('Y-m-d G:i:s');
        $data['ip'] = $remote->getIpAddress();
        $data['phone_number'] = '34'.$data['phone_number'];
        $data['iduser'] = $this->getUuid($data['email'])->__toString();
//         $this->verifyBySms($data);
        
        $processRegisterAs = $this->recordRegisterBy($data['email']);
//         print_r($processRegisterAs);
//         print_r($data);
    
         if($this->isRegistered($data['email']))
         {
             return new ApiProblemResponse(
                 new ApiProblem(
                     400 ,
                     'User already registered',
                     'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                     'Bad Request'
                 )
             );
         }
            
        if($processRegisterAs == 'do_nothing')
        {
            $userInfo = $this->getUserInfo($data['email'])->current();
            return new ApiProblemResponse(
                new ApiProblem(
                    400 ,
                    'User registered but not validated',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                    'Bad Request',
                    array('user'=>$userInfo)
                )
            );

        }
        else if($processRegisterAs == 'insert')
        {
            $out = $this->insertRegistry($data, $entity, $hydrator);
            if($out instanceof ApiProblemResponse)
                return new ApiProblemResponse(
                    $out->getApiProblem()
                );
        }
        else if ($processRegisterAs == 'update')
        {            
            $out = $this->updateRegistry($data, $entity, $hydrator);
            if($out instanceof ApiProblemResponse)
                return new ApiProblemResponse(
                    $out->getApiProblem()
                );
        }
               
        // $hydrator->hydrate($data, $entity);
        // $data['email']= $this->adapterMaster->getDriver()->getLastGeneratedValue();
        
        return $entity;
    }
    
    public function insertRegistry($data, $entity, $hydrator)
    {
        $tok  = openssl_random_pseudo_bytes(16, $strong);
        $token = base64_encode(bin2hex($tok));
        
        $action = new Insert($this->tableName);
        $data['password'] = $this->cryptPassword($data['password']);
        $data['token'] = $token;
        $data['code'] = mt_rand(1000,9999);
        $data['counter'] = 0;
        //             $data['iduser'] = $this->getUuid($data['email'])->__toString();
                
        $action->values($data);
        //var_dump($action->getSqlString());
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        $hydrator->hydrate($data, $entity);
        
        
        if($this->options->getIsEnableVerifyWithSms())
        {
            try{
                $this->verifyBySms($entity);
            }
            catch (DomainException $e)
            {
                return new ApiProblemResponse(
                    new ApiProblem(
                        $e->getCode(),
                        $e->getMessage(),
                        'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-429' ,
                        'Too many request'
                    )
                );
            }
        }        
        
        if($this->options->getIsEnableVerifyEmail())
            $this->mailer->sendVerificationEmail($entity);
        
        if($this->options->getIsEnableAutoverifyUser())
            $this->autoVerifyUser($entity);
        
        if($this->options->getIsEnableDeleteTemporalInfoAfterVerification())
            $this->delete($entity->email);
    }
    
    public function updateSms($data, $entity=null, $hydrator=null)
    {
        if($entity==null)
            $entity = new RegisterEntity();
        
        if($hydrator==null)
            $hydrator = new Reflection();
        
        $counter = $this->getCounterData($data['email']);
        $data['counter']=$counter + 1;
        $data['code'] = mt_rand(1000,9999);
        $data['password'] = $this->cryptPassword($data['password']);
        
        $action = new Update($this->tableName);
        $action->set($data);
        $action->where(array('email' => $data['email']));
        
        //var_dump($action->getSqlString());
        
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        $hydrator->hydrate($data, $entity);
        
        
        
        
        $entity = $this->getUserInfo($entity->email)->current();
        
//         print_r($entity->current());
        
        if($this->options->getIsEnableVerifyWithSms())
        {
            try{
                $this->verifyBySms($entity);
            }
            catch (DomainException $e)
            {
                return new ApiProblemResponse(
                    new ApiProblem(
                        $e->getCode(),
                        $e->getMessage(),
                        'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-429' ,
                        'Too many request'
                    )
                );
            }
        }
        
        if($this->options->getIsEnableVerifyEmail())
            $this->mailer->sendVerificationEmail($entity);
        
        if($this->options->getIsEnableAutoverifyUser())
            $this->autoVerifyUser($entity);
        
        if($this->options->getIsEnableDeleteTemporalInfoAfterVerification())
            $this->delete($entity->email);
        
        return $entity;
    }
    
    public function updateRegistry($data, $entity=null, $hydrator=null)
    {
        if($entity==null)
            $entity = new RegisterEntity();
        
        if($hydrator==null)
            $hydrator = new Reflection();
        
        $tok  = openssl_random_pseudo_bytes(16, $strong);
        $token = base64_encode(bin2hex($tok));
        $counter = $this->getCounterData($data['email']);
        
        $data['password'] = $this->cryptPassword($data['password']);
        $data['token'] = $token;
        $data['code'] = mt_rand(1000,9999);
        
        //             $max = new \Zend\Db\Sql\Expression("counter + 1");
        //             $data['counter']=$max;
        $data['counter']=$counter + 1;
        
        $action = new Update($this->tableName);
        $action->set($data);
        $action->where(array('email' => $data['email']));
        
        //var_dump($action->getSqlString());
        
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        $hydrator->hydrate($data, $entity);
        
        
        if($this->options->getIsEnableVerifyWithSms())
        {
            try{
                $this->verifyBySms($entity);
            }
            catch (DomainException $e)
            {
                return new ApiProblemResponse(
                    new ApiProblem(
                        $e->getCode(),
                        $e->getMessage(),
                        'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-429' ,
                        'Too many request'
                    )
                );
            }
        }
        
        if($this->options->getIsEnableVerifyEmail())
            $this->mailer->sendVerificationEmail($entity);
        
        if($this->options->getIsEnableAutoverifyUser())
            $this->autoVerifyUser($entity);
        
        if($this->options->getIsEnableDeleteTemporalInfoAfterVerification())
            $this->delete($entity->email);
    }
    
    public function verifyBySms($entity)
    {
        if($entity->getCounter() > ($this->options->getMaxNumberOfSms()))
            throw new DomainException('Validation SMSs reached', 429);
            
        
        $resource = $this->options->getSmsResource('messages');
        // print_r($resource);
        $data = array(
            "originator" => "Openy",
            "reference" => "The message to be sent",
            "body" => "Openy validation code: ".$entity->getCode(),
            "recipients" => $entity->phone_number
        );
        
        $this->apicaller->setHeaders($resource['headers']);
        $url = sprintf($this->options->getSmsUrl().$resource['endpoint']);
    	$value = $this->apicaller->getResponse($url, $data, Request::METHOD_POST);
    	
    	// print_r($data);
    	// print_r($value);
    	if($this->options->getIsEnableVerifyWithSmsSendEmail())
    	   $this->mailer->sendGenericEmail($entity, "Código de validación con SMS enviado al ".$entity->phone_number.": ".$entity->getCode());
    	
    	if(200 <= $value['code'] && 300 > $value['code'])
    	    return true;
    	else
    	    return false;
    	
    }
    
    private function autoVerifyUser($entity)
    {
        $sql = "INSERT INTO oauth_users (username, password, first_name, last_name, phone_number, iduser, token)
                        SELECT email, password, first_name, last_name, phone_number, iduser, token
                        FROM oauth_register
                        WHERE iduser='".$entity->iduser."' AND token ='".$entity->token."'";
        
        // echo $sql;
        $statement = $this->getAdapterMaster()->createStatement($sql);
        $result1 = $statement->execute();
        
        return $result1;
    }
    
    public function deleteByid($id)
    {
        if (empty($id)) {
            throw new DomainException('Invalid identifier provided', 404);
        }
         
        $delete = new Delete($this->tableName);
        $delete->where(array('iduser' => $id));
         
        $statement = $this->adapterMaster->createStatement();
        $delete->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        return true;
    }
    
    public function delete($id)
    {
    	if (empty($id)) {
    	    throw new DomainException('Invalid identifier provided', 404);
    	}
    	
    	$delete = new Delete($this->tableName);
    	$delete->where(array('email' => $id));    	
    	
    	$statement = $this->adapterMaster->createStatement();
    	$delete->prepareStatement($this->adapterMaster, $statement);
    	$driverResult = $statement->execute();
    	return true;
    }
    
    private function cryptPassword($password)
    {
        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->options->getPasswordCost());
        $password = $bcrypt->create($password);
    
        return $password;
    }
    
    private function getUuid($seed)
    {
        $uuid = Uuid::uuid5($this->options->getDnUuid(), $seed);
        
        return $uuid; 
    }
    
	/**
     * @return the $adapterMaster
     */
    public function getAdapterMaster()
    {
        return $this->adapterMaster;
    }

	

    
    
    
    
    
    
}
