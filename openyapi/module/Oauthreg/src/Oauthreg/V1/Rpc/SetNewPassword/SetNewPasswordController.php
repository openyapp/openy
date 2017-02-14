<?php
namespace Oauthreg\V1\Rpc\SetNewPassword;

use DomainException;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use Oauthreg\V1\Rest\Oauthuser\OauthuserEntity;
use Oauthreg\V1\Rest\Recoverpassword\RecoverpasswordEntity;
use Zend\Stdlib\Hydrator\Reflection;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\AbstractOptions;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;

use Zend\Crypt\Password\Bcrypt;

class SetNewPasswordController extends AbstractActionController
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;
    
    protected $registerMapper;
    protected $usersMapper;
    
    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, $registerMapper, $usersMapper, AbstractOptions $options)
    {
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
        $this->registerMapper   = $registerMapper;
        $this->usersMapper      = $usersMapper;
        $this->options		    = $options;
        
    }
    
    
    public function setNewPasswordAction()
    {
        $data = $this->bodyParams();
        $result = $this->verifyEmail($data['token'], $data['email']);
        if($result)
        {
            $entity = $this->patchUsers($data,$data['email']);
            $result = $this->deleteRegister($data['email']);
        }
        else
        {
            return new ViewModel(array(
                'result' => false, 
            ));
            //throw new DomainException('Invalid token or email provided', 500);
        }
        
        return new ViewModel(array(
            'result' => true, 
        ));
    }
    
    private function patchUsers($data, $id = null)
    {
        $data = (array)$data;
        
        if (!empty($id)) {
            $data['username'] = $id;
        }
        
        $entity = new OauthuserEntity();
        //                 \Zend\Debug\Debug::dump($entity, "entity before : ");
        $hydrator = new Reflection();
        $hydrator->hydrate($data, $entity);
        $data = $hydrator->extract($entity);
        //         \Zend\Debug\Debug::dump($data, "data after : ");
        
        $userData = $this->getUserName($id);
        $data['first_name'] = $userData->first_name;
        $data['last_name'] = $userData->last_name;
        
        if (isset($data['username'])) 
        {
            // update action
            $action = new Update('oauth_users');
            if(isset($data['password']))
                $data['password'] = $this->cryptPassword($data['password']);
            $action->set($data);
            $action->where(array('username' => $id));
        } 
        else 
            throw new DomainException('Invalid identifier provided', 404);
        
         
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        
        $hydrator->hydrate($data, $entity);
        return $entity;
    }
    
    public function deleteRegister($id)
    {
        if (empty($id)) {
            throw new DomainException('Invalid identifier provided', 404);
        }
         
        $delete = new Delete('oauth_register');
        $delete->where(array('email' => $id));
         
        $statement = $this->adapterMaster->createStatement();
        $delete->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        
        return true;
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
    
        $entity = new OauthuserEntity();
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype($entity);
        $resultSet->initialize($driverResult);
         
        if (0 === count($resultSet)) {
            return false;
        }
    
    
        $entity->populate($resultSet->current());
        return $entity;
    }
    
    private function cryptPassword($password)
    {
        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->options->getPasswordCost());
        $password = $bcrypt->create($password);
    
        return $password;
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
         
        $select = new Select('oauth_register');
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
}
