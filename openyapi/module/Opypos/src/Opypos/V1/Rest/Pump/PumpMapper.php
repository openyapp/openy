<?php 
namespace Opypos\V1\Rest\Pump;

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


class PumpMapper implements ServiceLocatorAwareInterface
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;    
    protected $priceMapper;
    private $stationEntity  = 'Opypos\V1\Rest\Pump\StationDataEntity';
    private $dataEntity     = 'Opypos\V1\Rest\Pump\PumpDataEntity';
    private $entity         = 'Opypos\V1\Rest\Pump\PumpEntity';
    private $collection     = 'Opypos\V1\Rest\Pump\PumpCollection';
    private $hydrator       = 'Zend\Stdlib\Hydrator\Reflection';
    protected $apicaller;
    
    use ServiceLocatorAwareTrait;
   
    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options, $apicaller, $priceMapper)
    {
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
        $this->options		    = $options;  
        $this->apicaller        = $apicaller;
        $this->priceMapper      = $priceMapper;
    }
    
    /*
    private function posFetch($id)
    {
        $out = array();
        $network = $this->options->getPosNetwork();
        if(!isset($network['opy_'.$id]['endpoint']))
            throw new DomainException('Not opy station set', 404);
        
        $url = $network['opy_'.$id]['endpoint']."/price";
        $value = $this->apicaller->getResponse($url);
        
        $data = $value['response']['_embedded']['price'];
        $out['prices'] = $data;
        $out['idopystation']=$id;
        return $data;
    }
    */
    /*
    private function posFetchPumpsstatus($id, $idpump = null)
    {
        $out = array();
        $network = $this->options->getPosNetwork();
        if(!isset($network['opy_'.$id]['endpoint']))
            throw new DomainException('Not opy station set', 404);
    
        $url = $network['opy_'.$id]['endpoint']."/pumpstatus";
        $value = $this->apicaller->getResponse($url);
    
        $data = $value['response']['_embedded']['pumpstatus'];
        
        //print_r($data);
        
        //         $out['idopystation']=$id;
        if($idpump != null)
            return $data[$idpump];
        else
            return $data;
    }
    */
    
    private function posFetchPumps($id)
    {
        $out = array();

        $network = $this->options->getPosNetwork();
        if(!isset($network['opy_'.$id]['endpoint']))
            throw new DomainException('Not opy station set', 404);
        
        $resource = $network['opy_'.$id]['resources']['sendorder'];
        $this->apicaller->setHeaders($resource['headers']);
        $url = sprintf($network['opy_'.$id]['endpoint']."pump");
        //print_r($url);
        $value = $this->apicaller->getResponse($url);
  
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
        
        
//         try
//         {
            $stationData = $this->getOpyStation($id);
//print_r($id);
// print_r($stationData);

            if(isset($stationData->idstation))
            {
                $id = $stationData->idstation;
                
//                 print_r($stationData);
//                 print_r($id);
                if($stationData->idopeny)
                    $data = $this->fetchPumps($stationData->idstation);
                
//                 print_r($data);
            }
                

//             print_r($stationData);
//             print_r($data);
//             $price = $this->priceMapper->fetch($id);
//             $data['promotions']=$price->current();
//             print_r($data);
                        
//         }
//         catch (\Exception $e)
//         {
//             die;
//             $openy = (array)$stationData->idopeny
//             if(!is_array($stationData))
//                 $stationData = (array)$stationData;
        if(is_array($stationData) && !$stationData['idopeny'])
        {
                
            
            $closeStation = $this->getCloseStation($point);
            
            return new ApiProblemResponse(
                new ApiProblem(
                    404 ,
                    'Not Opy station found',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404' ,
                    'Not found'
                    ,array('stationData'=>$stationData, 'closeStationData'=>$closeStation)
                )
            );
        }
        
        
        if($point)
        {
            
            $point = explode(',', $point);
//             print_r($point);
            
            
            if(!is_array($stationData))
                $stationData = (array)$stationData;
            
//             print_r($stationData);
//             die;     
                        if(!isset($stationData['ilat']) || !isset($stationData['ilng']))
                        {
                            return new ApiProblemResponse(
                                new ApiProblem(
                                    404,
                                    'Station not defined',
                                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                                    'Not found'                                    
                                )
                            );
                        }
                        
                	    if(isset($point[0]) and isset($point[1]))
                    	{
//                     	   print_r($stationData->ilat);
                    	   $stationData['idistance'] = $this->distance($stationData['ilat'],$stationData['ilng'],$point[0],$point[1], true, 'm');
                    	   $stationData['distance'] = $this->distance($stationData['ilat'],$stationData['ilng'],$point[0],$point[1]);
//                     	   print_r($distance);
//                     	   $stationData->distance = $distance;
                    	}
                        else
                        {
                            return new ApiProblemResponse(
                                new ApiProblem(
                                    404 ,
                                    'You are not in a gas station with Openy',
                                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404' ,
                                    'Not found'
                                    ,array('stationData'=>$stationData)
                                )
                            );
                        }
                        
                        if($stationData['idistance'] > $this->options->getMaxDistace())
                        {
                             return new ApiProblemResponse(
                                new ApiProblem(
                                    400 ,
                                    'You are not close to this station',
                                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                                    'Not found'
                                    ,array('stationData'=>$stationData)
                                )
                            );
                        }
                    	        
        }
        else
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    404 ,
                    'Unable to identify your location',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404' ,
                    'Not found'
                    ,array('stationData'=>$stationData)
                )
            );
        }
//         print_r($data);
                //print_r($stationData);
        
//         $driverResult['prices'] = $prices[0];
        $driverResult['station'] = $stationData;
        
        
        //$driverResult['ipumps'] = $data; // Collection
//print_r($data);       
 $collection2 = new \ZF\Hal\Collection($this->getProcessPumps($data, $id));
//print_r($collection2);        
        $driverResult['pumps'] = $collection2;
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
//         print_r($select->getSqlString());
    
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
//                     print_r($id);
//                     print_r(count($resultSet));
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
//                 var_dump($select->getSqlString());
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
        
        return $driverResult->current();
    }
    
    private function getCloseStation($point)
    {
       
        
        $filter = array('point'=>$point, 'order'=>'distance', 'filter'=>'openy');
        $listStationMapper = $this->serviceLocator->get('Openy\V1\Rest\Liststations\ListstationsMapper');
         
        $stations = $listStationMapper->fetchAll($filter);
        
//         print_r($stations->toJson());
        $data= json_decode($stations->toJson());
        $data = get_object_vars($data);
//         $data = get_object_vars($data);
//         print_r(get_object_vars($data[0]));
//         print_r(reset(get_object_vars($data)));
        return get_object_vars($data[0]);
//         return reset(get_object_vars($data));
        
         
    }
    
}
