<?php 
namespace Openy\V1\Rest\Station;

use DomainException;
use InvalidArgumentException;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Stdlib\AbstractOptions;

// use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Predicate\IsNotNull;
// use Zend\Db\Sql\Update;
// use Zend\Db\Sql\Insert;
// use Openy\Model\CleanStrategy;
// use Zend\Db\Sql\Delete;

class StationMapper 
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;      
    protected $currentUser;
    private $tableName      = 'off_station';
    private $entity         = 'Openy\V1\Rest\Station\StationEntity';
    private $collection     = 'Openy\V1\Rest\Station\StationCollection';
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
        $col = $this->collection;
        $col::setDefaultItemCountPerPage($this->options->jsonCollectionPerPageItems);
        
        $iduser = $this->currentUser->getUser('iduser');
        $columns = array ($select::SQL_STAR);
        
        if($iduser)
        {
                $select->join('opy_user_favorite_stations', 'opy_user_favorite_stations.idoffstation = off_station.idoffstation', 
                                array('favorite'=>new \Zend\Db\Sql\Expression("IF(opy_user_favorite_stations.iduser='".$iduser."',1,0)")
                              ), 'left');
        } 
        
        if(isset($filter['point']))
        {
            $point = explode(',', $filter['point']);
            if(isset($point[0]) and isset($point[1]))
            {
                $columns['distance'] = new \Zend\Db\Sql\Expression("( 6371 * acos( cos( radians(".$point[0].") ) * cos( radians( lat ) ) * cos( radians( lng ) -
                                                                   radians(".$point[1].") ) + sin( radians(".$point[0].") ) * sin( radians( lat ) ) ) )");
                $select->columns($columns);
                $select->order(array('distance ASC'));
            }
        }
        
        /**
         * Filters
         */
        if(isset($filter['psize']) && $filter['psize']=='all')
            $col::setDefaultItemCountPerPage(300000);
        elseif(isset($filter['psize']) )
            $col::setDefaultItemCountPerPage((int) $filter['psize']);
        
        if(isset($filter['action']))
        {
            switch($filter['action'])
            {
                case 'new':
                    $predicate = new  \Zend\Db\Sql\Where();
                    $select->where($predicate->greaterThan('created',$this->options->getInstallationDate()));
                    break;
                case 'update':
                    if(isset($filter['date']))
                    {
                        $predicate = new  \Zend\Db\Sql\Where();
                        $select->where($predicate->greaterThanOrEqualTo('updated',$filter['date']));
                    }
                    $select->where (array (new IsNotNull('updated')));
                    break;
                case 'delete':
                    
                    break;               
            }            
        }
        
        
//         echo $select->getSqlString();

    
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
    
    
    /**
     * @param string $id
     * @return Entity
     */
    public function fetch($id, $point = null)
    {
        if ($id == 0) {
    	    throw new DomainException('Invalid identifier provided', 404);
    	}        	
    	$fuelinfo = $this->getStationFuel();
    	    	
    	$select = new Select($this->tableName);
    	$select->where(array('idoffstation' => $id));    	
    	
//     	var_dump($select->getSqlString());
    	
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
    	
    	if (0 === count($resultSet)) {
    		throw new DomainException('Not found', 404);
    	}
    	
    	// Fueltype and Fuelprice for station
    	$entity->fueltypes = $fuelinfo['fueltypes'];
    	$entity->prices = $fuelinfo['prices'];
        
    	//Is station current user favorites?
//     	var_dump($this->isUserFavorite($id));
    	$entity->favorite = $this->isUserFavorite($id);
//     	if($this->isUserFavorite($id))
//     	    $entity->favorite = "1";
//     	else 
//     	    $entity->favorite = "0";
    	    
    	/**
    	 * Filters
    	 */

    	if($point)
    	{
    	    $point = explode(',', $point);
    	    if(isset($point[0]) and isset($point[1]))
    	    {
    	        $distance = $this->distance($resultSet->current()->ilat,$resultSet->current()->ilng,$point[0],$point[1]);
    	        $entity->distance = $distance;
    	    }
    	}
    	
    	return $resultSet->current();
    }  
    
    

    
    function distance($lat1, $lon1, $lat2, $lon2, $unit = 'K') {
    
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        
        switch($unit)
        {
            default:
            case 'm':
                $distance = ($miles * 1609.344);
                break;
            case 'K':
                $distance = ($miles * 1.609344);
                break;
            case 'N':
                $distance = ($miles * 0.8684);
                break;
            case 'M':
                $distance = $miles;
                break;
            case 'Y':
                $distance = ($miles * 1760);
                break;
            
        }
       
        if($unit == "K" and $distance < 1)
            $distance = number_format(($distance*1000), 0)."m";
        elseif($unit == "K")
            $distance = number_format(($distance),0)."km";
        
        if($unit == "M"  and $distance < 1)
            $distance = number_format(($distance*1760),0)."yd";
        elseif($unit == "M")
            $distance = number_format(($distance),0)."mi";
        
        return $distance;
        
    }
    
    
    
    private function isUserFavorite($idStation)
    {
        $iduser = $this->currentUser->getUser('iduser');
        $select = new Select('opy_user_favorite_stations');
        
        if(!$iduser)
            return null;
        
        $select->where(array('iduser' => $iduser, 'idoffstation'=>$idStation));
        //var_dump($select->getSqlString());
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
        
        $resultSet = new HydratingResultSet;
        $resultSet->initialize($driverResult);
        
        //print_r($resultSet->current());
       
        if(empty($resultSet->current()))
            return 0;
        else
            return 1;
    }
    
    private function getStationFuel()
    {
        $select = new Select('off_price');
        $select->columns(array( 'idoffstation',
            'fueltypes' => new \Zend\Db\Sql\Expression('GROUP_CONCAT(idfueltype)'),
            'prices' => new \Zend\Db\Sql\Expression('GROUP_CONCAT(iprice)'),
        
        ) );
        
        $select->group(array('idoffstation'));
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
        
        $resultSet = new HydratingResultSet;
        $resultSet->initialize($driverResult);
        
        return $resultSet->current();
    }
    
    
    
    
    
    
    
    
    
}
