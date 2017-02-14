<?php
namespace Openy\V1\Rpc\VerifyPin;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ContentNegotiation\JsonModel;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Select;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class VerifyPinController extends AbstractActionController implements ServiceLocatorAwareInterface
{
    protected $preferenceMapper;
    protected $options;
    
    use ServiceLocatorAwareTrait; 
    
    public function __construct($preferenceMapper,  $adapterMaster, $adapterSlave, $options)
    {
        $this->preferenceMapper = $preferenceMapper;
        $this->options          = $options;
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
    }
    
    public function verifyPinAction()
    {
        $inputPin = $this->params()->fromRoute('pin');
        if(empty($inputPin))
            throw new \Exception ("Not valid pin.", 404);
        
        $iduser = $this->params()->fromRoute('iduser');
        if(empty($iduser))
            throw new \Exception ("Not iduser provided.", 404);
        
        return $this->verifyPin($iduser, $inputPin);
        
        
        
    }
    
    public function verifyPin($iduser, $inputPin)
    {
//         die("kaka");
//         echo $iduser;
        $preference = $this->preferenceMapper->fetch($iduser);
        if($preference instanceof ApiProblemResponse)
            return $preference;
        
        try 
        {
            
            if($preference->payment_pin != $inputPin)
            {
                $counter = $this->incrementCounter($iduser);
                $tries = $this->options->getMaxNumberOfPinsTries() - $counter;
//                 var_dump($tries);
                if($tries === 0) 
                {
                    $preference=array("default_credit_card"=>null);
                    $this->preferenceMapper->patch($iduser, $preference);
                    $this->getServiceLocator()->get('Openy\Service\CreditCard')->deleteAll();                    
                }                    
                throw new \Exception ("Not valid pin.", 500);                
            }
            
        }
        catch (\Exception $e)
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    400 ,
                    'Not valid pin',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                    'Bad Request',
                    array('attempts_remaining'=>$tries)
                )
            );
        }
    
        $this->resetCounter($iduser);
        return new JsonModel(array(
            'result' => true,
        ));
        
    }
    
    private function resetCounter($iduser)
    {
        $action = new Update('opy_user_counter');
        $action->set(array('pin_tries'=>0, 'pin_updated'=>date('Y-m-d G:i:s')), 'set');
        $action->where(array('iduser'=>$iduser));
         
        //echo $action->getSqlString();
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult2 = $statement->execute();
        return;
    }
    
    private function incrementCounter($iduser)
    {
        $select = new Select('opy_user_counter');
        $select->where(array('iduser' => $iduser));
        //var_dump($select->getSqlString());
         
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
        
        if( time() > strtotime($driverResult->current()['pin_updated'])+$this->options->getDaysToResetPinsTries())
        {
            $this->resetCounter($iduser);
        }
        
        if($driverResult->current()['iduser']!=null)
        {
            $max = new \Zend\Db\Sql\Expression("pin_tries + 1");
            $data = array('iduser'=>$iduser, 'pin_tries'=> $max, 'pin_updated'=>date('Y-m-d G:i:s'));
            
            $action = new Update('opy_user_counter');
            $action->set($data, 'set');
            $action->where(array('iduser'=>$iduser));
             
            //echo $action->getSqlString();
            $statement = $this->adapterMaster->createStatement();
            $action->prepareStatement($this->adapterMaster, $statement);
            $driverResult = $statement->execute();
        }
        else
        {
            $data = array('iduser'=>$iduser, 'pin_tries'=> 1, 'pin_updated'=>date('Y-m-d G:i:s'));
            $action = new Insert('opy_user_counter');
            $action->values($data);
             
            //echo $action->getSqlString();
            $statement = $this->adapterMaster->createStatement();
            $action->prepareStatement($this->adapterMaster, $statement);
            $driverResult = $statement->execute();
        }
            
                            
        $select = new Select('opy_user_counter');
        $select->where(array('iduser' => $iduser));
        //var_dump($select->getSqlString());
         
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
        
        //print_r($driverResult->current());
        
        return $driverResult->current()['pin_tries'];
    }
}
