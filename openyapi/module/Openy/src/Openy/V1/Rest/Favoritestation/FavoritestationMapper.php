<?php 
namespace Openy\V1\Rest\Favoritestation;

use DomainException;
use InvalidArgumentException;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Delete;

use Zend\Db\Adapter\AdapterInterface; 
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\AbstractOptions;

class FavoritestationMapper 
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;   
    protected $currentUser;
    private $tableName      = 'opy_user_favorite_stations';
    private $entity         = 'Openy\V1\Rest\Favoritestation\FavoritestationEntity';
    private $collection     = 'Openy\V1\Rest\Favoritestation\FavoritestationCollection';
    private $hydrator       = 'Zend\Stdlib\Hydrator\Reflection';
    
    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options, $currentUser)
    {
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
        $this->options		    = $options;  
        $this->currentUser      = $currentUser;
    }
    
    public function fetchAll($filter)
    {
        
        $select = new Select($this->tableName);
               
        /**
         * Filters
        */
        

        if(isset($filter['iduser']))
            $select->where(array('iduser'=>$filter['iduser']));            
        
        if(isset($filter['idoffstation']))
            $select->where(array('idoffstation'=>$filter['idoffstation']));
       
          
        //echo $select->getSqlString();
    
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
    
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();        
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
        
        $resultset = new HydratingResultSet;
        $resultset->setHydrator($hydrator);
        $resultset->setObjectPrototype($entity);
        $resultset->initialize($driverResult); 
        
        $paginatorAdapter = new DbSelect(
            $select,
            $this->adapterSlave,
            $resultset
        );
        
        $class = new \ReflectionClass($this->collection);
        $collection = $class->newInstance($paginatorAdapter);
        
        return $collection;
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
        
        $data->idfavorite=$this->adapterMaster->getDriver()->getLastGeneratedValue();
    
        // $hydrator->hydrate((array)$data, $entity);
        return $data;
    }
    
    private function recordAlreadyRegistered($data)
    {
        $data->iduser=$this->currentUser->getUser('iduser');
        
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
        
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();
    
        $select = new Select($this->tableName);
        $select->where(array('iduser' => $data->iduser, 
                             'idoffstation'=>$data->idoffstation
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
    
    public function deleteList($data)
    {
        $data['iduser']=$this->currentUser->getUser('iduser');
        
        if (empty($data) or !isset($data['iduser']) or !isset($data['idoffstation'])) {
            throw new DomainException('Invalid identifier provided', 404);
        }
         
        $action = new Delete($this->tableName);
        $action->where(array('iduser' => $data['iduser'], 
                             'idoffstation'=>$data['idoffstation']
                      ));
        //var_dump($action->getSqlString());
        
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        
        return true;
    }
    
}
