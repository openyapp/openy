<?php 
namespace Openy\V1\Rest\Preference;

use DomainException;
use InvalidArgumentException;

use Zend\Stdlib\AbstractOptions;
use Zend\Paginator\Adapter\DbSelect;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Delete;
use Zend\Db\Metadata\Metadata;

use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Db\Adapter\AdapterInterface; 
use Zend\Db\ResultSet\HydratingResultSet;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

use Openy\Interfaces\Openy\Preferences\PreferencesByUserInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class PreferenceMapper implements PreferencesByUserInterface, ServiceLocatorAwareInterface
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;      
    protected $currentUser;
    
    private $tableName      = 'opy_user_preference';
    private $entity         = 'Openy\V1\Rest\Preference\PreferenceEntity';    
    private $collection     = 'Openy\V1\Rest\Preference\PreferenceCollection';
    private $hydrator       = 'Zend\Stdlib\Hydrator\Reflection';
    private $idColumn       = 'iduser';
    
    use ServiceLocatorAwareTrait;
    
    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options, $currentUser)
    {
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
        $this->options		    = $options;    
        $this->currentUser      = $currentUser;
    }
   
    /**
     * @param string $id
     * @return Entity
     */
    public function fetch($id)
    {
        $iduser = $this->currentUser->getUser('iduser');
        
        if ($id !== $iduser) {
//             throw new DomainException('Invalid identifier provided', 404);
            return new ApiProblemResponse(
                new ApiProblem(
                    400 ,
                    'Invalid identifier provided',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                    'Bad Request'
                )
            );
        }
        
        $select = new Select($this->tableName);
        $select->where(array($this->idColumn => $id));
        
        //var_dump($select->getSqlString());
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
         
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
        
        
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();
         
         
        $resultSet = new HydratingResultSet;
        $resultSet->setHydrator($hydrator);
        $resultSet->setObjectPrototype($entity);
        $resultSet->initialize($driverResult);
         
        if (0 === count($resultSet)) 
        {
            $entity->iduser = $id;
//             print_r($entity);
//             echo "kakaka".$id;
            return $entity;
            //throw new DomainException('Not found', 404);
        }
         
   
        return $resultSet->current();
    }
    
    public function save($data)
    {
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
    
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();
    
        if($this->recordAlreadyRegistered($data))
            return true;
    
        $action = new Insert($this->tableName);
    
        $data->iduser = $this->currentUser->getUser('iduser');
        $action->values((array)$data);
        //var_dump($action->getSqlString());
         
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
    
//         $data->idfavorite=$this->adapterMaster->getDriver()->getLastGeneratedValue();
    
        // $hydrator->hydrate((array)$data, $entity);
        return true;
    }
    
    private function recordAlreadyRegistered($data)
    {
        $data->iduser=$this->currentUser->getUser('iduser');
    
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
    
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();
    
        $select = new Select($this->tableName);
        $select->where(array('iduser' => $data->iduser
        ));
        //var_dump($select->getSqlString());
         
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
         
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype($entity);
        $resultSet->initialize($driverResult);
    
        if (0 === count($resultSet))
            return false;
        else
            return true;
    }
    
    public function patch($id, $data)
    {
        $iduser = $this->currentUser->getUser('iduser');
        
        if ($id !== $iduser) {
            //             throw new DomainException('Invalid identifier provided', 404);
            return new ApiProblemResponse(
                new ApiProblem(
                    400 ,
                    'Invalid identifier provided',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                    'Bad Request'
                )
            );
        }
//         print_r($id);
//         print_r($data);
        $preferences = $this->fetch($id);
        
//         if(!$preferences)
//         {
            if(!$this->save($data))
            {
                return new ApiProblemResponse(
                    new ApiProblem(
                        400 ,
                        'Invalid preferences',
                        'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                        'Bad Request'
                    )
                );
            }
//         }
        
// print_r($data);
// die;

        $data = (array)$data;
    	$pin_is_null = (array_key_exists('payment_pin', $data) && $data['payment_pin']===NULL);
    	if ($pin_is_null)
    		$data['default_credit_card'] = null;
       
        // For security reason unset iduser.
//         unset($data['iduser']);
        
        // Clean data. Unset value if not a valid column
//         $data = $this->cleanColumns($data);   

        // update action
        try {
            $action = new Update($this->tableName);
            $action->set($data, 'set');
            $action->where(array($this->idColumn => $id));
            
            //var_dump($action->getSqlString());
            
            $statement = $this->adapterMaster->createStatement();
            $action->prepareStatement($this->adapterMaster, $statement);
            $driverResult = $statement->execute();
        }
        catch (\Exception $e)
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    400 ,
                    'Invalid preferences',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                    'Internal Server Error'
                )
            );
        }
        

        if($pin_is_null)
        {
            try{
                $this->getServiceLocator()->get('Openy\Service\CreditCard')->deleteAll();                
            }
            catch (\Exception $e) {
                return new ApiProblemResponse(
                    new ApiProblem(
                        500 ,
                        'Some error deleting user credit cards',
                        'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-500' ,
                        'Internal Server Error'
                    )
                );
            }
               
        }

        return $this->fetch($id);
    }      
    
    /**
     * This method is extremely SLOW 
     * 
     * @return multitype:
     */
    private function getColumns1()
    {
        $metadata = new Metadata($this->adapterMaster);
        $columns = $metadata->getColumnNames($this->tableName);
        return $columns;
    }
    
    private function getColumns()
    {
        $sql = "describe ".$this->tableName;
        $statement = $this->adapterMaster->createStatement($sql);
        $result1 = $statement->execute();
        
        foreach ($result1 as $row)
            $columns[]=$row['Field'];
        
        return $columns;
    }
    
    private function cleanColumns($data)
    {
        $columns = $this->getColumns();        
        foreach (array_keys($data) as $key)
        {
            if(!in_array($key, $columns))
                unset($data[$key]);
        }
        return $data;
    }
    
    public function getPreferences($iduser)
    {
        return $this->fetch($iduser);
    }    
    
}
