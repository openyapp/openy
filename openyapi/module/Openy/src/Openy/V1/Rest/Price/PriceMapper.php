<?php 
namespace Openy\V1\Rest\Price;

use DomainException;
use InvalidArgumentException;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\AdapterInterface; 
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\AbstractOptions;
use Zend\Db\Sql\Predicate\IsNotNull;

class PriceMapper 
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;      
    private $tableName      = 'off_price';
    private $entity         = 'Openy\V1\Rest\Price\PriceEntity';
    private $collection     = 'Openy\V1\Rest\Price\PriceCollection';
    private $hydrator       = 'Zend\Stdlib\Hydrator\Reflection';
    
    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options)
    {
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
        $this->options		    = $options;    
    }
    
    public function fetchAll($filter)
    {
        
        $select = new Select($this->tableName);
        $col = $this->collection;
        $col::setDefaultItemCountPerPage($this->options->jsonCollectionPerPageItems);
        
        $select->columns(array( 'idoffstation', 
                                'fueltypes' => new \Zend\Db\Sql\Expression('GROUP_CONCAT(idfueltype)'),
                                'prices' => new \Zend\Db\Sql\Expression('GROUP_CONCAT(iprice)'),
                
        ) );
        
        $select->group(array('idoffstation'));
        
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
    
}
