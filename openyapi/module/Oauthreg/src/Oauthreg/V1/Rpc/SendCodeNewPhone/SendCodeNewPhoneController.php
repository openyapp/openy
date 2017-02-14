<?php
namespace Oauthreg\V1\Rpc\SendCodeNewPhone;

use Zend\Mvc\Controller\AbstractActionController;
use DomainException;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;

use ZF\ContentNegotiation\JsonModel;
use Zend\Db\ResultSet\HydratingResultSet;
use Oauthreg\V1\Rest\Register\RegisterEntity;
use Zend\Stdlib\Hydrator\Reflection;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class SendCodeNewPhoneController extends AbstractActionController
{
    
    protected $registerMapper;
    protected $options;
    protected $adapterMaster;
    protected $adapterSlave;
    
    public function __construct($registerMapper,  $adapterMaster, $adapterSlave, $options)
    {
        $this->registerMapper   = $registerMapper;
        $this->options          = $options;
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
    }
    
    public function sendCodeNewPhoneAction()
    {
        $data = $this->bodyParams();
        
        $result = $this->verifySms($data['iduser']);
    
        if($result)
        {
            $entity = $this->patchUsers($data,$data['iduser']);
            //             print_r($entity);
    
            if($this->options->getIsEnableVerifyWithSms())
                $sendSms = $this->registerMapper->verifyBySms($entity);
    
            if(!$sendSms)
            {
                return new ApiProblemResponse(
                    new ApiProblem(
                        412 ,
                        'Too many validation intents',
                        'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-412' ,
                        'Too many intents'
                    )
                );
            }
    
            //             return true;
            return new JsonModel(array(
                'result' => true,
            ));
        }
        else
        {
            //             return false;
            return new JsonModel(array(
                'result' => false,
            ));
            //throw new DomainException('Invalid token or email provided', 500);
        }
    }
    
    private function incrementCounter($id)
    {
        $entity = $this->getUserData($id);
        // TODO Clear counter if updated is more than 2 days.
        $data['counter']=$entity->counter+1;
        $action = new Update('oauth_register');
        $action->set($data);
        $action->where(array('iduser' => $entity->iduser));
        //var_dump($action->getSqlString());
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        return;
    }
    
    private function resetCounter($id)
    {
        $entity = $this->getUserData($id);
        
        if( time() > strtotime($entity->updated)+86400)
            $data['counter'] = null;
        else
            $data['counter']=$entity->counter;
        
        $action = new Update('oauth_register');
        $action->set($data);
        $action->where(array('iduser' => $entity->iduser));
        //var_dump($action->getSqlString());
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        return;
    }
    
    private function patchUsers($data, $id)
    {
        $this->resetCounter($id);
        $data = (array)$data;
        
        $entity = new RegisterEntity();
        //                 \Zend\Debug\Debug::dump($entity, "entity before : ");
        $hydrator = new Reflection();
        //         $hydrator->hydrate($data, $entity);
        //         $data = $hydrator->extract($entity);
        //         \Zend\Debug\Debug::dump($data, "data after : ");
    
        //         $userData = $this->getUserName($id);
        //         $data['first_name'] = $userData->first_name;
        //         $data['last_name'] = $userData->last_name;
        $data['code'] = mt_rand(1000,9999);
        $data['phone_number'] = $data['new_phone_number'];
        unset($data['iduser']);
        unset($data['new_phone_number']);
        
            // update action
            $action = new Update('oauth_register');
            $action->set($data);
            $action->where(array('iduser' => $id));
        
        //var_dump($action->getSqlString());
         
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
    
        $data = $this->getUserData($id);
        //         print_r($data);
        $this->incrementCounter($id);
        $hydrator->hydrate((array)$data, $entity);
    
        return $entity;
    }
    
    
    private function getUserData($id)
    {
        if (empty($id)) {
            return false;
        }
         
        $select = new Select('oauth_register');
        $select->where(array('iduser' => $id));
        //var_dump($select->getSqlString());
    
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
    
        $entity = new RegisterEntity();
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype($entity);
        $resultSet->initialize($driverResult);
         
        if (0 === count($resultSet)) {
            return false;
        }
    
    
        $entity->populate($resultSet->current());
        return $entity;
    }
    
    // Verify if user is on oauth_register
    public function verifySms($iduser)
    {
        
        if (empty($iduser)) {
            throw new DomainException('Invalid iduser provided', 404);
        }
    
        // User already register
        if(!$this->registerMapper->isRegisteredByid($iduser))
            return false;
         
        $select = new Select('oauth_register');
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
    
        // Get data
        $entity = new RegisterEntity();
        $entity->populate($resultSet->current());
    
        return true;
    
    
    }
}
