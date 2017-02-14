<?php 
namespace Opypos\V1\Rest\Configuration;

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


class ConfigurationMapper implements ServiceLocatorAwareInterface
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;      
    private $entity         = 'Opypos\V1\Rest\Configuration\ConfigurationEntity';
    private $collection     = 'Opypos\V1\Rest\Configuration\ConfigurationCollection';
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
	
	/*
		$resource = $this->stationInfo['resources']['sendorder'];
        $this->apicaller->setHeaders($resource['headers']);
        $url = sprintf($this->stationInfo['endpoint'].$resource['endpoint']);
        $value = $this->apicaller->getResponse($url,  $data, Request::METHOD_POST);
        return $value;
	*/	
		
		
        $network = $this->options->getPosNetwork();
        if(!isset($network['opy_'.$id]['endpoint']))
            throw new DomainException('Not opy station set', 404);
        
		$resource = $network['opy_'.$id]['resources']['configuration'];
		$this->apicaller->setHeaders($resource['headers']);
		$url = sprintf($network['opy_'.$id]['endpoint'].$resource['endpoint']);
		$value = $this->apicaller->getResponse($url);
        
		$data = $value['response']['_embedded']['configuration'];
        $data[0]['idopystation']=$id;
        
        return $data;
    }
    
    
    
    
    public function fetch($id)
    {
        
        try
        {
            $idin = $id;
            $id = $this->getOpyStation($id);
            $driverResult = $this->posFetch($id);  
            $driverResult[0]['idoffstation']=$idin;
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

        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
        
        $adapter = $this->getAdapter($id);
        $entity = $adapter->exchangeConfigArray($entity, $driverResult);
        
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();         
         
        $resultSet = new HydratingResultSet;
        $resultSet->setHydrator($hydrator);
        $resultSet->setObjectPrototype($entity);
        $resultSet->initialize($driverResult);
         
        if (0 === count($resultSet)) {
            return false;
            //throw new DomainException('Not found', 404);
        }
         
        return $resultSet->current();
    }
    
    private function getOpyStation($id)
    {
        $select = new Select('opy_station');
        $select->where(array('idoffstation' => $id));
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
