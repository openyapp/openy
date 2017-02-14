<?php
namespace Oauthreg\V1\Rpc\VerifySmsNewPhone;

use Zend\Mvc\Controller\AbstractActionController;

use DomainException;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\HydratingResultSet;
use Oauthreg\V1\Rest\Register\RegisterEntity;

use ZF\ContentNegotiation\JsonModel;

class VerifySmsNewPhoneController extends AbstractActionController
{
    protected $registerMapper;
    protected $options;
    
    public function __construct($registerMapper,  $adapterMaster, $adapterSlave, $options)
    {
        $this->registerMapper   = $registerMapper;
        $this->options          = $options;
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
    }
    
    public function verifySmsNewPhoneAction()
    {
        $code = $this->params()->fromRoute('code');
        if(empty($code))
            throw new \Exception ("Not valid code.", 404);
    
        $iduser = $this->params()->fromRoute('iduser');
        if(empty($iduser))
            throw new \Exception ("Not iduser provided.", 404);
    
        $result = $this->registerMapper->verifyNewSms($code, $iduser);
    
        if($result)
        {
            $data = $this->getUserData($iduser);
            
            $action = new Update('oauth_users');
            $action->set(array('phone_number'=>$data->phone_number));
            $action->where(array('iduser' => $iduser));
        
            //var_dump($action->getSqlString());
             
            $statement = $this->adapterMaster->createStatement();
            $action->prepareStatement($this->adapterMaster, $statement);
            $driverResult = $statement->execute();
            
            if (0 === count($driverResult->count())) {
                throw new DomainException('Not found', 404);
            }  
            return new JsonModel(array(
                'result' => true,
            ));
        }
        return new JsonModel(array(
            'result' => false,
        ));
    
        
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
    
   
}
