<?php 
namespace Openy\V1\Rest\Liststations;

use DomainException;
use InvalidArgumentException;

use Zend\Stdlib\AbstractOptions;
use Zend\Paginator\Adapter\DbSelect;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Db\Adapter\AdapterInterface; 
use Zend\Db\ResultSet\HydratingResultSet;

class ListstationsMapper 
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;      
    protected $currentUser;
    private $tableName      = 'off_station';
    private $entity         = 'Openy\V1\Rest\Liststations\ListstationsEntity';
    private $collection     = 'Openy\V1\Rest\Liststations\ListstationsCollection';
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
        $select = new Select(array('off_station'=>$this->tableName));
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
        
        /**
         * Filters
         */
        if(isset($filter['point']))
        {
            $point = explode(',', $filter['point']);
            if(isset($point[0]) and isset($point[1]))
            {
                $columns['distance'] = new \Zend\Db\Sql\Expression("( 6371 * acos( cos( radians(".$point[0].") ) * cos( radians( lat ) ) * cos( radians( lng ) -
                                                                   radians(".$point[1].") ) + sin( radians(".$point[0].") ) * sin( radians( lat ) ) ) )");                
            }
        }
        
        /**
         * Fueltype
         */
        if(isset($filter['ftype']))
        {
            $select->join('off_price', 'off_price.idoffstation = off_station.idoffstation', 
                          array('price'=>'price',
                                'fueltype'=>'idfueltype'
                                ), 'left');
            $select->join('off_fueltype', 'off_fueltype.idfueltype = off_price.idfueltype',
                array('fuelcode'=>'fuelcode',
                ), 'left');
            $select->where (array ('off_fueltype.fuelcode'=>$filter['ftype']));
        }
        
        /**
         * Where
         */
        if(isset($filter['filter']))
        {
            $orders = explode(",", $filter['filter']);
            foreach($orders as $order)
            {
                switch($order)
                {
                    case 'recommended':
                        $select->where (array ('recommended'=>'1'));
                        break;
                    case 'openy':
                        $select->where (array ('idopeny'=>'1'));
                        break;
                    case 'favorite':
                        $columns['iduser'] = 'opy_user_favorite_stations.iduser';
                        $select->where (array (new \Zend\Db\Sql\Predicate\IsNotNull('iduser')));
                        break;
                }
            }
        }
        
        
        /**
         * Order By 
         */
        if(isset($filter['order']))
        {
            $orders = explode(",", $filter['order']);
            foreach($orders as $order)
            {
                switch($order)
                {
                    case 'price':
                        if(isset($filter['ftype']))
                            $select->order(array('price ASC'));
                        break;
                    case 'distance':
                        $select->order(array('distance ASC'));
                        break;          
                }
            }
        }
        
        /**
         * Distance as second order by if point is present
         */
        if(isset($filter['point']))     
        {
            $select->order(array('distance ASC'));
        }
        
        
        $select->columns($columns);
//         var_dump($select->getSqlString());

        
        
        
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
//                     $select->where($predicate->greaterThanOrEqualTo('created',$this->options->getInstallationDate()));
                    $select->where($predicate->greaterThan('created',$this->options->getInstallationDate()));
                    break;
                case 'update':
//                     $select->where($predicate->greaterThanOrEqualTo('created',$this->options->getInstallationDate()));
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
    
        // Remove favorite if authorization header is not present 
//         if(!$iduser)
//             unset($entity->favorite);

        // Remove distance if point query is not present
//         if(!$entity->distance)
//             unset ($entity->distance);
        
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
    
    
    
    
    
    
    
    
    
    
    
    
    
}
