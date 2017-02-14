<?php 
namespace Opypos\V1\Rest\Price;

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


class PriceMapper implements ServiceLocatorAwareInterface
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;      
    private $entity         = 'Opypos\V1\Rest\Price\PriceEntity';
    private $stationEntity         = 'Opypos\V1\Rest\Price\StationEntity';
    private $clientEntity         = 'Opypos\V1\Rest\Price\ClientEntity';
    private $collection     = 'Opypos\V1\Rest\Price\PriceCollection';
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
        $out = array();
        
        $network = $this->options->getPosNetwork();
        if(!isset($network['opy_'.$id]['endpoint']))
            throw new DomainException('Not opy station set', 404);
        
        $resource = $network['opy_'.$id]['resources']['sendorder'];
        $this->apicaller->setHeaders($resource['headers']);
        $url = sprintf($network['opy_'.$id]['endpoint']."price");
        //print_r($url);
        $value = $this->apicaller->getResponseMute($url);
                
        $data = $value['response']['_embedded']['price'];
        $out['prices'] = $data;
        $out['idopystation']=$id;
        return $data;
    }
    
    private function getClient($id, $userInfo)
    {
        $network = $this->options->getPosNetwork();
        if(!isset($network['opy_'.$id]['endpoint']))
            throw new DomainException('Not opy station set', 404);
        
        $url = $network['opy_'.$id]['endpoint']."client/".$userInfo['username'];
        $value = $this->apicaller->getResponseMute($url);
        
        $adapter = $this->getAdapter($id);
		//print_r($value);
		//die;
		if(isset($value['status']) && ($value['status']>=300 or $value['status']<200))
		{
			// Error on API.
			// Check if openy_user is set
			$user_openy = $value['APIdata']['body']['openy_user'];
			//print_r($user_openy);
			$url = $network['opy_'.$id]['endpoint']."client/".$user_openy['EMAIL'];
			$value = $this->apicaller->getResponse($url);
		}
		//die;
        foreach ($value as $key => $result)
        {
            if($key=='response')
            {
                $class = new \ReflectionClass($this->clientEntity);
                $entity = $class->newInstance();
                $results[] = $adapter->exchangeClientArray($entity, $result);
            }
            
        
        }
        
       
        return $results[0];
    }
    
    private function posFetchPrices($id, $idclient)
    {
        $out = array();
        $network = $this->options->getPosNetwork();
        if(!isset($network['opy_'.$id]['endpoint']))
            throw new DomainException('Not opy station set', 404);
    
        if(isset($idclient))
            $url = $network['opy_'.$id]['endpoint']."price?idclient=".$idclient;
        else
            $url = $network['opy_'.$id]['endpoint']."price";
        
        $value = $this->apicaller->getResponse($url);
    
        $data = $value['response']['_embedded']['price'];

        return $data;
    }
    
//     public function fetchPrices2($id)    //WORKING
//     {
//         try
//         {
//             $id = $this->getOpyStation($id);
//             $driverResult = $this->posFetchPrices($id);
//         }
//         catch (\Exception $e)
//         {
//             return new ApiProblemResponse(
//                 new ApiProblem(
//                     404 ,
//                     'Not Opy station found',
//                     'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404' ,
//                     'Not found'
//                 )
//             );
//         }
    
//         $class = new \ReflectionClass($this->entity);
//         $entity = $class->newInstance();
    
//         $class = new \ReflectionClass($this->hydrator);
//         $hydrator = $class->newInstance();
        
//         $resultset = new HydratingResultSet;
//         $resultset->setHydrator($hydrator);
//         $resultset->setObjectPrototype($entity);
//         $resultset->initialize($driverResult);
    
//         return $resultset->toArray(); 
    
//     }
    
    private function setOpyStation($id, $data)
    {
        $driverResult = array();
        foreach($data as $result)
        {
            $result['idopystation']=$id;
            $driverResult[]=$result;
        }
        return $driverResult;
    }
    
    public function fetchPrices($id, $idclient)
    {

        $driverResult = $this->posFetchPrices($id, $idclient);
        $driverResult = $this->setOpyStation($id, $driverResult);
        
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
       
    
        $adapter = $this->getAdapter($id);
        foreach ($driverResult as $result)
        {
            $class = new \ReflectionClass($this->entity);
            $entity = $class->newInstance();
            $results[] = $adapter->exchangePriceArray($entity, $result);
            
        }
        
        //return $results;
        
        $paginatorAdapter = new ArrayAdapter($results);
    
        $class = new \ReflectionClass($this->collection);
        $collection = $class->newInstance($paginatorAdapter);
        
        return $collection;
    }
    
    
    
    public function fetch($id, $openy=null)
    {
        $userInfo = $this->getServiceLocator()->get('Oauthreg\Service\CurrentUser')->getUser();
        if(!$userInfo)
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    400 ,
                    'Invalid user provided',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                    'Bad Request'
                )
            );
        }
        
        try
        {
            $id = $this->getOpyStation($id);
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
		
        
        try
        {
            //            $id = $this->getOpyStation($id);
            $clientInfo = $this->getClient($id, $userInfo);        
        }
        catch (\Exception $e)
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    404 ,
                    'Not Opy station set',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404' ,
                    'Not found'
                )
            );
        }
        //print_r($clientInfo);
        $idclient = $clientInfo->posIdClient;
        if(isset($openy) && $openy ==='openy')
            $idclient=null;
        elseif(isset($openy))
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    400 ,
                    'Invalid user provided',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                    'Bad Request'
                )
            );
        }
       
        try
        {
//            $id = $this->getOpyStation($id);
            $prices = $this->fetchPrices($id, $idclient);
        
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
        $driverResult['idClient'] = ($idclient==null)?'openy':$idclient;
        $driverResult['prices'] = $prices; // Collection
        $driverResult['idopystation'] = $id;
//         print_r($driverResult);
        
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();
        
        $class = new \ReflectionClass($this->stationEntity);
        $entity = $class->newInstance();
        
        $hydrator->hydrate($driverResult, $entity);        
        return $entity;
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
