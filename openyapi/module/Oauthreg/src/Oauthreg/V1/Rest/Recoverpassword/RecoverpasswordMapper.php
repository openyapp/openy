<?php 
namespace Oauthreg\V1\Rest\Recoverpassword;

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

use Zend\Crypt\Password\Bcrypt;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class RecoverpasswordMapper 
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;      
    private $tableName = 'oauth_register';
    protected $mailer;
    
    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options, $mailer)
    {
        $this->adapterMaster = $adapterMaster;
        $this->adapterSlave  = $adapterSlave;
        $this->options		 = $options;    
        $this->mailer        = $mailer;
    }
   
    
    public function isRegistered($email)
    {
        $select = new Select('oauth_users');
        $select->where(array('username' => $email));
         
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
        //var_dump($select->getSqlString());
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype(new RecoverpasswordEntity);
        $resultSet->initialize($driverResult);
        
        if (0 === count($resultSet)) {
            return false;
        }
        
        return true;
    }
    
    public function verifyEmail($token, $email)
    {   
        if(!$this->isRegistered($email))
            return false;
        
        if (empty($token)) {
            throw new DomainException('Invalid token provided', 404);
        }
        
        if (empty($email)) {
            throw new DomainException('Invalid identifier provided', 404);
        }
         
        $select = new Select($this->tableName);
        $select->where(array('token' => $token, 'email' => $email));
        // var_dump($select->getSqlString());
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
         
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype(new RecoverpasswordEntity);
        $resultSet->initialize($driverResult);
        
        if (0 === count($resultSet)) {
            return false;
        }
        
        // Get uer data
        $entity = new RecoverpasswordEntity();
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
    
//     public function fetchAll($filter)
//     {
//         $select = new Select($this->tableName);
            
//         /**
//          * Filters
//          */
//         isset($filter['email'])?$select->where(array('email' => $filter['email'])):null;
        
//         $resultset = new HydratingResultSet;
//         $resultset->setObjectPrototype(new RecoverpasswordEntity);
    
//         $paginatorAdapter = new DbSelect(
//             $select,
//             $this->adapterSlave,
//             $resultset
//         );
    
//         $collection = new RecoverpasswordCollection($paginatorAdapter);
//         return $collection;
//     }
    
    /**
     * @param string $id
     * @return Entity
     */
//     public function fetch($id)
//     {
//         if (empty($id)) {
//     	    throw new DomainException('Invalid identifier provided', 404);
//     	}        	
    	
//     	$select = new Select($this->tableName);
//     	$select->where(array('email' => $id));    	
//     	//var_dump($select->getSqlString());
    	
//     	$statement = $this->adapterSlave->createStatement();
//     	$select->prepareStatement($this->adapterSlave, $statement);
//     	$driverResult = $statement->execute();
    	
//     	$resultSet = new HydratingResultSet;
//     	$resultSet->setObjectPrototype(new RecoverpasswordEntity);    	
//     	$resultSet->initialize($driverResult); 
    	
//     	if (0 === count($resultSet)) {
//     		throw new DomainException('Not found', 404);
//     	}

//     	$entity = new RecoverpasswordEntity();
//     	$entity->populate($resultSet->current());
//     	return $entity;
//     }   
    
//     public function update($data, $id = null)
//     {
//         $data = (array)$data;
        
//         if (!empty($id)) {
//             $data['email'] = $id;
//         }
        
//         $entity = new RecoverpasswordEntity();
// //         \Zend\Debug\Debug::dump($entity, "entity before : ");
        
//         $hydrator = new Reflection();
//         $hydrator->hydrate($data, $entity);
//         //$entity->populate($data);
         
// //         \Zend\Debug\Debug::dump($entity, "entity after: ");
        
// //         \Zend\Debug\Debug::dump($hydrator->extract($entity), "entity extracted: ");
//         $data = $hydrator->extract($entity);
        
// //         \Zend\Debug\Debug::dump($data, "data: ");
        
//         if (isset($data['email'])) {
//             // update action
//             $action = new Update($this->tableName);
//             $action->set($data);
//             $action->where(array('email' => $id));
//         } 
//         //      var_dump($action->getSqlString());
         
//         $statement = $this->adapterMaster->createStatement();
//         $action->prepareStatement($this->adapterMaster, $statement);
//         $driverResult = $statement->execute();
        
//         $data['email']= $this->adapterMaster->getDriver()->getLastGeneratedValue();
        
//         $hydrator->hydrate($data, $entity);
         
//         return $entity;
//     }
    
    private function recordRegisterBy($id)
    {
        $entity = new RecoverpasswordEntity();
        
        $select = new Select($this->tableName);
        $select->where(array('email' => $id));
        //var_dump($select->getSqlString());
         
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
         
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype(new RecoverpasswordEntity);
        $resultSet->initialize($driverResult);
        
        if (0 === count($resultSet)) 
            return 'insert';
        
        $entity = new RecoverpasswordEntity();
    	$entity->populate($resultSet->current());
        
    	// Dont do any if user abuse of button. One day inactive
        if( time() < strtotime($entity->created)+86400)
            return 'do_nothing';
        
        
        
        return 'update';      
    }
    
    private function getUserName($id)
    {
        if (empty($id)) {
            return false;
        }
         
        $select = new Select('oauth_users');
        $select->where(array('username' => $id));
        //var_dump($select->getSqlString());
         
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
         
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype(new RecoverpasswordEntity);
        $resultSet->initialize($driverResult);
         
        if (0 === count($resultSet)) {
            return false;
        }
    
        $entity = new RecoverpasswordEntity();
        $entity->populate($resultSet->current());
        return $entity;
    }
    
    public function insert($data)
    {
        $data = (array)$data;
        $remote = new RemoteAddress();
    
        $entity = new RecoverpasswordEntity();        
        $hydrator = new Reflection();
        $hydrator->hydrate($data, $entity);
        //$entity->populate($data);         
        $data = $hydrator->extract($entity);       
        
        
        $processRegisterAs = $this->recordRegisterBy($data['email']);
        
//         \Zend\Debug\Debug::dump($user);
//         \Zend\Debug\Debug::dump($processRegisterAs);
        
        if($processRegisterAs=='insert')
        {
            // Not register yet.
            return new ApiProblemResponse(
                new ApiProblem(
                    404,
                    'User not found',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404' ,
                    'Not found'
                )
            );
            return false;
//             $tok  = openssl_random_pseudo_bytes(16, $strong);
//             $token = base64_encode(bin2hex($tok));
            
//             $action = new Insert($this->tableName);
//             $data['type'] = 1;
//             $data['token'] = $token;            
            
            
//             $action->values($data);
//             //var_dump($action->getSqlString());
//             $statement = $this->adapterMaster->createStatement();
//             $action->prepareStatement($this->adapterMaster, $statement);
//             $driverResult = $statement->execute();
//             $hydrator->hydrate($data, $entity);
            
//             // Send verification email
//             $this->mailer->sendRecoverPasswordEmail($entity);
        }
        else if ($processRegisterAs=='update')
        {    

            $data['created'] = date('Y-m-d G:i:s');
            
            $user = $this->getUserName($data['email']);
            $data['first_name'] = $user->first_name;
            $data['last_name'] = $user->last_name;
            $data['ip'] = $remote->getIpAddress();
            
            
            $tok  = openssl_random_pseudo_bytes(16, $strong);
            $token = base64_encode(bin2hex($tok));
            
            $action = new Update($this->tableName);
            $data['type'] = 1;
            $data['token'] = $token;
            
            
            $action->set($data);
            $action->where(array('email' => $data['email']));
            //var_dump($action->getSqlString());
            $statement = $this->adapterMaster->createStatement();
            $action->prepareStatement($this->adapterMaster, $statement);
            $driverResult = $statement->execute();
            $hydrator->hydrate($data, $entity);
            
            // Send verification email
            if($this->options->getSentRecoverPasswordLinkEmail())
                $this->mailer->sendRecoverPasswordEmail($entity);
            
            if($this->options->getSentRecoverPasswordToEmail())
            {
                $pass  = openssl_random_pseudo_bytes(6, $strong);
                $entity->newPassword = base64_encode(bin2hex($pass));
                
                $data2['password'] = $this->cryptPassword($entity->newPassword);
                $action = new Update('oauth_users');
                $action->set($data2);
                $action->where(array('username' => $data['email']));
                //var_dump($action->getSqlString());
                 
                $statement = $this->adapterMaster->createStatement();
                $action->prepareStatement($this->adapterMaster, $statement);
                $driverResult = $statement->execute();
                
                
                
                
                
                $this->mailer->sendRecoverPasswordToEmail($entity);    
                unset($entity->newPassword);      
            }
            
            // Force logout by expiring access_token & refresh_token
            $this->revoke($data['email']);
            // TODO: push App to login screen
                
            
            
        }
      
        
        $hydrator->hydrate($data, $entity);
        

        // $data['email']= $this->adapterMaster->getDriver()->getLastGeneratedValue();
        
        return $entity;
    }
    
    public function revoke($userid)
    {
        // Exprires Access token
        $data['expires'] = date('Y-m-d G:i:s');
        $action = new Update('oauth_access_tokens');
        $action->set($data);
        $action->where(array('user_id' => $userid));
        //var_dump($action->getSqlString());
         
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        
        if (0 === count($driverResult->count())) {
            throw new DomainException('Not found', 404);
        }
        
        // Exprires Refresh token
        $data['expires'] = date('Y-m-d G:i:s');
        $action = new Update('oauth_refresh_tokens');
        $action->set($data);
        $action->where(array('user_id' => $userid));
        //var_dump($action->getSqlString());
         
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        
        if (0 === count($driverResult->count())) {
    		throw new DomainException('Not found', 404);
    	}
            
        return true;
    }
    
//     public function delete($id)
//     {
//     	if (empty($id)) {
//     	    throw new DomainException('Invalid identifier provided', 404);
//     	}
    	
//     	$delete = new Delete($this->tableName);
//     	$delete->where(array('email' => $id));    	
    	
//     	$statement = $this->adapterMaster->createStatement();
//     	$delete->prepareStatement($this->adapterMaster, $statement);
//     	$driverResult = $statement->execute();
//     	return true;
//     }
    
    private function cryptPassword($password)
    {
        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->options->getPasswordCost());
        $password = $bcrypt->create($password);
    
        return $password;
    }
    
	/**
     * @return the $adapterMaster
     */
//     public function getAdapterMaster()
//     {
//         return $this->adapterMaster;
//     }

	

    
    
    
    
    
    
}
