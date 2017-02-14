<?php 
namespace Opypos\V1\Rest\Pumpstatus;

use DomainException;
use InvalidArgumentException;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\AdapterInterface; 
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\AbstractOptions;
use Zend\Db\Sql\Predicate\IsNotNull;

use Zend\Paginator\Adapter\ArrayAdapter;

use Zend\Http\Request;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;


class PumpstatusMapper implements ServiceLocatorAwareInterface
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;      
    private $entity         = 'Opypos\V1\Rest\Pumpstatus\PumpstatusEntity';
    private $dataentity         = 'Opypos\V1\Rest\Pumpstatus\PumpstatusDataEntity';
    private $collection     = 'Opypos\V1\Rest\Pumpstatus\PumpstatusCollection';
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
    
    private function posFetch($id)
    {
        $network = $this->options->getPosNetwork();
        if(!isset($network['opy_'.$id]['endpoint']))
            throw new DomainException('Not opy station set', 404);
        
        $resource = $network['opy_'.$id]['resources']['sendorder'];
        $this->apicaller->setHeaders($resource['headers']);
        $url = sprintf($network['opy_'.$id]['endpoint']."pumpstatus");
        //print_r($url);
        $value = $this->apicaller->getResponseMute($url);
        
        $data = $value['response']['_embedded']['pumpstatus'];
//         $data['idopystation']=$id;
        return $data;
    }
    
    
    public function fetchPumpstatus($id)
    {
    
    
        $driverResult = $this->posFetch($id);
    
//         $driverResult[0]['idopystation']=1;
//         $driverResult[1]['idopystation']=1;
//                 print_r($driverResult);
    
    
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
         
    
        $adapter = $this->getAdapter($id);
        foreach ($driverResult as $key => $result)
        {
            $result['IDSURTIDOR']=$key;
            $result['idopystation']=$id;
            //print_r($result);
            $class = new \ReflectionClass($this->dataentity);
            $entity = $class->newInstance();
            
            $results[] = $adapter->exchangePumpstatusArray($entity, $result);
    
        }
//         print_r($results);
//         return $results;
    
        $paginatorAdapter = new ArrayAdapter($results);
    
        $class = new \ReflectionClass($this->collection);
        $collection = $class->newInstance($paginatorAdapter);
    
        return $collection;
    }
    
    public function fetch($id)
    {
        
        try
        {
            $id = $this->getOpyStation($id);
            $status = $this->fetchPumpstatus($id);
        
        }
        catch (\Exception $e)
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    404 ,
                    'Not Opy station found',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404' ,
                    'Not found'
                )
            );
        }
        
        
        
//         $driverResult['prices'] = $prices[0];
        $driverResult['status'] = $status; // Collection
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
        $select->where->like('idoffstation', $id);
        //var_dump($select->getSqlString());
    
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
    
        if (0 === count($driverResult)) {
            throw new DomainException('Not opy station set', 404);
        }
    
        return $driverResult->current()['idstation'];
    }
    
    private function getAdapter($id)
    {
        $network = $this->options->getPosNetwork();
        $adapter = $network['opy_'.$id]['adapter'];
        return $this->getServiceLocator()->get($adapter);
    }
    
}
