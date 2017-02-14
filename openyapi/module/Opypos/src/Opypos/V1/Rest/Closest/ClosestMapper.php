<?php 
namespace Opypos\V1\Rest\Closest;

use DomainException;
use InvalidArgumentException;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\AdapterInterface; 
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\AbstractOptions;
use Zend\Db\Sql\Predicate\IsNotNull;

use Zend\Http\Request;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

use Zend\Paginator\Adapter\ArrayAdapter;
use Opypos\V1\Rest\Configuration\ConfigurationEntity;


class ClosestMapper implements ServiceLocatorAwareInterface
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;    
    protected $closestMapper;
    private $stationEntity  = 'Opypos\V1\Rest\Closest\StationDataEntity';
    private $dataEntity     = 'Opypos\V1\Rest\Closest\PumpDataEntity';
    private $entity         = 'Opypos\V1\Rest\Closest\ClosestEntity';
    private $collection     = 'Opypos\V1\Rest\Closest\ClosestCollection';
    private $hydrator       = 'Zend\Stdlib\Hydrator\Reflection';
    protected $apicaller;
    
    use ServiceLocatorAwareTrait;
   
    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options, $apicaller)
    {
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
        $this->options		    = $options;  
        $this->apicaller        = $apicaller;
    }
    
        
    private function posFetchPumps($id)
    {
        $out = array();
        $network = $this->options->getPosNetwork();
        if(!isset($network['opy_'.$id]['endpoint']))
            throw new DomainException('Not opy station set', 404);
    
        $url = $network['opy_'.$id]['endpoint']."pump";
        $value = $this->apicaller->getResponse($url);
//print_r($value);    
        $data = $value['response']['_embedded']['pump'];
//         $out['idopystation']=$id;
        return $data;
    }
    
    
    public function fetchPumps($id)
    {
   
        $driverResult = $this->posFetchPumps($id);
//         print_r($driverResult);    
    
        $adapter = $this->getAdapter($id);
        foreach ($driverResult as $result)
        {
            $class = new \ReflectionClass($this->dataEntity);
            $entity = $class->newInstance();
            $results[] = $adapter->exchangePumpArray($entity, $result);
            
        }
       //  print_r($results);
//         return $results;
        
        $paginatorAdapter = new ArrayAdapter($results);
    
        $class = new \ReflectionClass($this->collection);
        $collection = $class->newInstance($paginatorAdapter);
//print_r($collection);       
        return $collection;
    }
    
    function distance($lat1, $lon1, $lat2, $lon2, $onlydistance = false, $unit = 'K') {
    
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
        
        if($onlydistance)
            return $distance;
            
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
    
    
     public function fetch($id, $point = null)
    {        
        
        
        try
        {
            
            if($point)
            {
                $point2 = explode(',', $point);
                if(!isset($point2[0]) or !isset($point2[1]))
                {
                    throw new DomainException('Not location set', 404);
                }
            }
            else
                throw new DomainException('Not location set', 404);
        }
        catch (\Exception $e)
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    404 ,
                    'Not location set',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404' ,
                    'Not found'
                )
            );
        }
        
        
        try
        {
            $stationData = $this->getOpyStation($id);
//print_r($id);
//print_r($stationData);

            if(isset($stationData->idstation))
                $id = $stationData->idstation;

            
 
//             $data = $this->fetchPumps($id);


//             print_r($data);
//             $price = $this->priceMapper->fetch($id);
//             $data['promotions']=$price->current();
//             print_r($data);
            $closeStation = $this->getCloseStation($point);
                        
        }
        catch (\Exception $e)
        {
            
            
            
//             return new ApiProblemResponse(
//                 new ApiProblem(
//                     404 ,
//                     'Not Opy station found',
//                     'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404' ,
//                     'Not found'
//                     ,array('stationData'=>$stationData, 'closeStationData'=>$closeStation)
//                 )
//             );
        }
        
        
       
//         print_r($data);
                //print_r($stationData);
        
//         $driverResult['prices'] = $prices[0];
        $driverResult['station'] = $stationData;
        $driverResult['closeStationData'] = $closeStation;
        
        
        //$driverResult['ipumps'] = $data; // Collection
//print_r($data);       
 //$collection2 = new \ZF\Hal\Collection($this->getProcessPumps($data, $id));
//print_r($collection2);        
        //$driverResult['pumps'] = $collection2;
        $driverResult['idopystation'] = $id;
//         print_r($driverResult);
        
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();
        
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
        
        $hydrator->hydrate($driverResult, $entity);        
        return $entity;
    }
    
    private function getOpyStation($id)
    {
        
        $select = new Select('opy_station');

        $select->columns(array( 'idcompany', 'idoffstation', 'idstation'));
        $select->join('off_station', 'off_station.idoffstation = opy_station.idoffstation', 
                    array('name'=>'name', 'logoname'=>'logoname', 'address'=>'address', 'idopeny', 'ilat'=>'ilat', 'ilng'=>'ilng'),
                    'left');
        $select->where(array('opy_station.idoffstation' => $id));
        //var_dump($select->getSqlString());
    
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
    
                    $class = new \ReflectionClass($this->stationEntity);
                    $entity = $class->newInstance();
                     
                    $class = new \ReflectionClass($this->hydrator);
                    $hydrator = $class->newInstance();
                     
                     
                    $resultSet = new HydratingResultSet;
                    $resultSet->setHydrator($hydrator);
                    $resultSet->setObjectPrototype($entity);
                    $resultSet->initialize($driverResult);
                    
                    if (0 === count($resultSet)) {
//                         throw new DomainException('Not opy station set', 404);
                        return $this->getOffStationData($id);
                    }
                    return $resultSet->current();
        
//         if (0 === count($driverResult)) {
// //             throw new DomainException('Not opy station set', 404);
//             return $this->getOffStationData($id);
//         }
    
//         return $driverResult->current();
    }
    
    private function getAdapter($id)
    {
        $network = $this->options->getPosNetwork();
        $adapter = $network['opy_'.$id]['adapter'];
        return $this->getServiceLocator()->get($adapter);
    }
    
    private function getProcessPumps($data, $idopystation)
    {
//print_r($data->getCurrentItems());
//die;
//print_r($data->toJson());
//die;
//print_r($data);       
 $data= json_decode($data->toJson());
        
   //     print_r($data);
        
        $pumps = array();
        foreach ($data as $pump)
        {
//             print_r($pump);
            $id =$pump->idpump;
//             $pumps[$id]['labels'][$pump->opyIdProductType]=$pump->posLabel;
//             $status = $this->posFetchPumpsstatus($idopystation, $id);
//             print_r($status);
//             $pumps[$id]['productType']=$pump->opyIdProductType;
//             $pumps[$id]['status']= $this->posFetchPumpsstatus($idopystation, $id);
            //$pumps[$id][$pump->opyIdProductType]=$pump->price;
            $pumps[$id]['idPump'] = $id;
            $pumps[$id]['status'] = $pump->opySatus;
            $pumps[$id]['product'][] =$pump->opyIdProductType;
//             $pumps[$id]['price'][] = array('price'=>$pump->price);
            
            
//             $pumps[$id][]['opyIdProductType']=$pump->opyIdProductType;
            
        }
//print_r($pumps);
//echo "kak";
        return $pumps;
    }
    
    private function getOffStationData($id)
    {
        $select = new Select('off_station');
        
        $select->columns(array('name'=>'name', 'logoname'=>'logoname', 'address'=>'address', 'idopeny'));
       
        $select->where(array('off_station.idoffstation' => $id));
        //         var_dump($select->getSqlString());
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
        
        return $driverResult->current();
    }
    
    private function getCloseStation($point)
    {
       
        
        $filter = array('point'=>$point, 'order'=>'distance');
        $listStationMapper = $this->serviceLocator->get('Openy\V1\Rest\Liststations\ListstationsMapper');
         
        $stations = $listStationMapper->fetchAll($filter);
        
        
        $data= json_decode($stations->toJson());
//         print_r(reset(get_object_vars($data)));
        
        return reset(get_object_vars($data));
        
         
    }
    
}
