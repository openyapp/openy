<?php

namespace ElemApicaller\Service;

use Traversable;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;

use Zend\Http\Client;
use Zend\Json\Json;
use Zend\Http\Request;
use Zend\Json\Decoder as JsonDecoder;
use Zend\Json\Encoder as JsonEncoder;
use Zend\Http\Client\Adapter\Curl;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class ApiCaller implements EventManagerAwareInterface
{
    /**
     * @var EventManagerInterface
     */
    protected $events;   
    protected $headers = null;
    protected $client = null;
    protected $decodeType = Json::TYPE_ARRAY;
    protected $options;
    protected $serviceManager;
    
    public function __construct($options, $serviceManager)
    {
        $this->options = $options;
        $this->serviceManager = $serviceManager;
    }
          
    /**
     * @return the $decodeType
     */
    public function getDecodeType()
    {
        return $this->decodeType;
    }
    
    /**
     * @param number $decodeType
     */
    public function setDecodeType($decodeType)
    {
        $this->decodeType = $decodeType;
        return $this;
    }
    
    /**
     * @return the $headers
     */
    public function getHeaders() {
        return $this->headers;
    }
    
    /**
     * @param field_type $headers
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
        return $this;
    }
    
    public function addHeaders($headers)
    {
        if(!isset($this->headers))
            $this->headers = $headers;
        else
            $this->headers = array_merge($this->headers,$headers);
    
        return $this->headers;
    }
    
    public function getClientInstance()
    {
        if ($this->client === null) {
            $this->client = new Client();
			$this->client->setOptions(array('maxredirects' => 0,'timeout' => 30));
            //            $this->client->setAdapter(new Curl());
            // 			self::$client->setAdapter(new Curl());
            // 			self::$client->setEncType(Client::ENC_URLENCODED);
        }
    
        return $this->client;
    }
    
    public function setToken()
    {
        if($this->serviceManager->has('ElemAuth\Service'))
        {
            $authService = $this->serviceManager->get('ElemAuth\Service');
            if($authService->hasIdentity())
            {   
                $token = $authService->getStorage()->getToken();
                $this->addHeaders(array('Authorization'=> 'Bearer '.$token['access_token']));
            }  
        }
        
        return;        
    }
    
    public function getResponse($url, array $postData = null,  $method = Request::METHOD_GET)
    {
        $headers = array('Accept' 			=> 'application/json',
            'Content-Type' 	=> 'application/json',
            'Accept-Encoding'	=> 'UTF-8',
        );
        
        $this->setToken();
         
        if(isset($this->headers))
            $headers= $this->getHeaders() + $headers;
        
        $client = $this->getClientInstance();
        $client->setUri($url);
        $client->setMethod($method);
        
        $secure=$client->getUri()->getScheme()=='https';
        if($secure)
        {
            $curl = new Curl();
            //$curl->setOptions(array('sslverifypeer'=>false));
            //curl_setopt ($curl, CURLOPT_CAINFO, __DIR__ . "/cacert.pem");
            $this->client->setAdapter($curl);
        }
            
        
        if ($postData !== null)
            $client->setParameterPost($postData);
         
        $client->setHeaders($headers);
        
        if ($postData !== null) {
            $client->setRawBody(Json::encode($postData, true));
            $client->setEncType('application/json');
        }
        $response = $client->send();
        
        if($_SERVER['APPLICATION_ENV'] =='development')
        {
            // TODO remove log and set in a event
            $logger = new Logger;
            $logger->addWriter(new Stream('data/logs/apiclient.log'));
            $logger->debug($method.' '.$url.' '.serialize($headers).' '.serialize($postData));
            $logger->debug($response->getStatusCode().' '.$response->getBody());
        }
        
        if ($response->isSuccess())
        {
            return array ('code' => $response->getStatusCode(),
                          'response' => JsonDecoder::decode($response->getBody(), $this->getDecodeType())                         
            );
        }
        else
        {
            try {
                $body = JsonDecoder::decode($response->getBody(), $this->getDecodeType());                
                throw new \RuntimeException('Some problem using the API. Check the Endpoint you are calling.');
            } catch (\Exception $e)
            {
                
                if($_SERVER['APPLICATION_ENV'] =='development')
                {
                    echo 'Caught exception: ', $e->getMessage(), "\n\n";
                    echo $method.' '.$url."\n";
                    echo 'Status: '. $response->getStatusCode().' '.$response->getReasonPhrase()."\n";
                    echo 'Error body: ';
                    print_r($body);
                    echo "\n";
                    
//                     die;
//                     ob_start();
//                     print_r($body);
//                     $body = ob_get_contents();
//                     ob_end_clean();
//                     $msg =  $method.' '.$url."\n";
//                     $msg .=  'Status: '. $response->getStatusCode().' '.$response->getReasonPhrase()."\n";
//                     $msg .=  'Error body: ';
//                     $msg .= $body;
//                     $msg .= "\n";
                }
            }
        
        }
    }
    
    public function getResponseMute($url, array $postData = null,  $method = Request::METHOD_GET)
    {
        $headers = array('Accept' 			=> 'application/json',
            'Content-Type' 	=> 'application/json',
            'Accept-Encoding'	=> 'UTF-8',
        );
    
        $this->setToken();
         
        if(isset($this->headers))
            $headers= $this->getHeaders() + $headers;
    
        $client = $this->getClientInstance();
        $client->setUri($url);
        $client->setMethod($method);
    
        $secure=$client->getUri()->getScheme()=='https';
        if($secure)
        {
            $curl = new Curl();
            //$curl->setOptions(array('sslverifypeer'=>false));
            //curl_setopt ($curl, CURLOPT_CAINFO, __DIR__ . "/cacert.pem");
            $this->client->setAdapter($curl);
        }
    
    
        if ($postData !== null)
            $client->setParameterPost($postData);
         
        $client->setHeaders($headers);
    
        if ($postData !== null) {
            $client->setRawBody(Json::encode($postData, true));
            $client->setEncType('application/json');
        }
        $response = $client->send();
    
        if($_SERVER['APPLICATION_ENV'] =='development')
        {
            // TODO remove log and set in a event
            $logger = new Logger;
            $logger->addWriter(new Stream('data/logs/apiclient.log'));
            $logger->debug($method.' '.$url.' '.serialize($headers).' '.serialize($postData));
            $logger->debug($response->getStatusCode().' '.$response->getBody());
        }
    
        if ($response->isSuccess())
        {
            return array ('code' => $response->getStatusCode(),
                'response' => JsonDecoder::decode($response->getBody(), $this->getDecodeType())
            );
        }
        else
        {
            try {
                $body = JsonDecoder::decode($response->getBody(), $this->getDecodeType());
                throw new \RuntimeException('Some problem using the API. Check the Endpoint you are calling.', 500);
            } 
            catch (\Exception $e)
            {   
                if($_SERVER['APPLICATION_ENV'] =='development')
                {
                    return array('status'=>$e->getCode(), 'message'=>$e->getMessage(),
                                    'APIdata'=>array('url'=>$method.' '.$url,
                                                  'status'=>$response->getStatusCode(),
                                                  'reason'=>$response->getReasonPhrase(),
                                                  'body'=>$body
                                    )
                    );
                }
                else
                    return array('status'=>$e->getCode(), 'message'=>$e->getMessage());
            }
    
        }
    }
    
    public function doRequest($url, array $postData = null,  $method = Request::METHOD_GET)
    {
        $headers = array('Accept' 			=> 'application/json',
            'Content-Type' 	=> 'application/json',
            'Accept-Encoding'	=> 'UTF-8',
        );
        
        $this->setToken();
         
        if(isset($this->headers))
            $headers= $this->getHeaders() + $headers;

        $client = $this->getClientInstance();
        $client->setUri($url);
        $client->setMethod($method);
    
        if ($postData !== null)
            $client->setParameterPost($postData);
         
        $client->setHeaders($headers);
    
        if ($postData !== null) {
            $client->setRawBody(Json::encode($postData, true));
            $client->setEncType('application/json');
        }
        $response = $client->send();
    
        if($_SERVER['APPLICATION_ENV'] =='development')
        {
            // TODO remove log and set in a event
            $logger = new Logger;
            $logger->addWriter(new Stream('data/logs/apiclient.log'));
            $logger->debug($method.' '.$url.' '.serialize($headers).' '.serialize($postData));
            $logger->debug($response->getStatusCode().' '.$response->getBody());
        }
    
        //return JsonDecoder::decode($response->getBody(), $this->getDecodeType());
        
        if ($response->isSuccess())
        {
            return JsonDecoder::decode($response->getBody(), $this->getDecodeType());
        }
        else
        {
            try {
                $body = JsonDecoder::decode($response->getBody(), $this->getDecodeType());                
                throw new \RuntimeException('Some problem using the API. Check the Endpoint you are calling.');
            } catch (\Exception $e)
            {
                echo 'Caught exception: ', $e->getMessage(), "\n\n";
                if($_SERVER['APPLICATION_ENV'] =='development')
                {
                    echo $method.' '.$url."\n";
                    echo 'Status: '. $response->getStatusCode().' '.$response->getReasonPhrase()."\n";
                    echo 'Error body: ';
                    print_r($body);
                    echo "\n";
                    
                    die;
                }
            }
        
        }
        
    }
    
//     public function returnData($data, $returnType = 'Array')
//     {
//         switch ($returnType)
//         {
//             case 'Array';
//                 return $data;
//             break;
            
//             case 'Object';
//                 return $this->convertToObject($data);
//             break;
            
//             case 'Json';
//                 return JsonEncoder::encode($data);
//             break;
            
//             case 'Embedded';
//                 return array_shift(array_shift($data["_embedded"]));
//             break;
    
//             // FIXME: return paginator information inside collection
// //             case 'Collection';
// //             return new $this->getCollection(new HydratingArrayPaginator($data, $this->hydrator, $this->prototype));
// //             break;
    
//             // This type changes the page size. The size page here must be equals or less than the API response
// //             case 'Paginator';
// //             $iteratorAdapter = new ArrayAdapter($data);
// //             $paginator = new Paginator($iteratorAdapter);
// //             $paginator->setItemCountPerPage(25);
// //             return $paginator;
// //             break;
    
//             // This type losts paginator information
// //             case 'ResultSet':
// //                 $resultset = new HydratingResultSet($this->hydrator, $this->prototype);
// //                 $resultset->initialize($data);
// //                 return $resultset;
// //                 break;
    
// //             case 'Entity':
// //                 $this->hydrator->hydrate($data, $this->prototype);
// //                 return $this->prototype;
// //             break;
    
            
//         }
    
//         return false;
//     }
    
    private function convertToObject($array) {
        $object = new \stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->convertToObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    }
    /**
     * Set the event manager instance used by this context
     *
     * @param  EventManagerInterface $events
     * @return mixed
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $identifiers = array(__CLASS__, get_called_class());
        if (isset($this->eventIdentifier)) {
            if ((is_string($this->eventIdentifier))
                || (is_array($this->eventIdentifier))
                || ($this->eventIdentifier instanceof Traversable)
            ) {
                $identifiers = array_unique(array_merge($identifiers, (array) $this->eventIdentifier));
            } elseif (is_object($this->eventIdentifier)) {
                $identifiers[] = $this->eventIdentifier;
            }
            // silently ignore invalid eventIdentifier types
        }
        $events->setIdentifiers($identifiers);
        $this->events = $events;
        return $this;
    }
    
    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }
}