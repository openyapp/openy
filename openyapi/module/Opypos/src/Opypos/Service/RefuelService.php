<?php
namespace Opypos\Service;

// use Zend\Db\Sql\Update;
// use Zend\Db\Sql\Insert;
// use Zend\Db\Sql\Select;
// use ZF\ApiProblem\ApiProblemResponse;
// use Openy\Exception;

// use Zend\Http\Request;
// use Zend\Mail\Transport\Smtp as SmtpTransport;
// use Zend\Mail\Transport\SmtpOptions;
// use Zend\Mail\Message;
// use Zend\View\Model\ViewModel;
// use Zend\View\Renderer\PhpRenderer;
// use Zend\View\Resolver;

// use Zend\Mime\Message as MimeMessage;
// use Zend\Mime\Part as MimePart;

use DomainException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class RefuelService implements ServiceLocatorAwareInterface
{
    protected $options;
    protected $apicaller;
    protected $stationService;
    protected $user;
    protected $userPreferences;
    protected $orderService;
    protected $logger = null;
    
    use ServiceLocatorAwareTrait;
    
    public function __construct($options, $apicaller, $currentUser, $currentPreferences, $orderService)
    {
        $this->options = $options;
        $this->apicaller = $apicaller;  
        $this->user = $currentUser->getUser();              
        $this->userPreferences = $currentPreferences->getPreference();
        $this->orderService = $orderService;
        if($_SERVER['APPLICATION_ENV'] =='development')
        {
            // TODO remove log and set in a event
            $this->logger = new Logger;
            $this->logger->addWriter(new Stream('data/logs/refuellog.log'));
        }
    }
    
    
    public function setPrice($iduser, $data ,$order=null)
    {
        $stationService = $this->getStationService($data->idopystation);
        $val = $stationService->setPrice($data);
        return $val;
    }
    
    public function presetAmount($iduser, $data ,$order=null)
    {
        $stationService = $this->getStationService($data->idopystation);
        $val = $stationService->presetAmount($data);
        return $val;
    }
    
    public function monitorRaisePump($idopystation, $pump)
    {
        $stationService = $this->getStationService($idopystation);
        return $stationService->monitorRaisePump($idopystation, $pump);
    }
    
    public function cancellOrderTimeoutRaise($idopystation, $pump, $order)
    {
        $order = $this->getServiceLocator()->get('Openy\OrderMapper')->fetch($order);
        $order = $this->orderService->cancelOrder($order);
        
        $stationService = $this->getStationService($idopystation);
        return $stationService->cancellSupply($idopystation, $pump);
    }
    
    public function monitorFuelPumped($idopystation, $pump)
    {
        $stationService = $this->getStationService($idopystation);
        return $stationService->monitorFuelPumpued($idopystation, $pump);
    }
    
    public function monitorHangPump($idopystation, $pump)
    {
        $stationService = $this->getStationService($idopystation);
        $pumpStatus = $stationService->monitorHangPump($idopystation, $pump);
        $finish = $this->finishRefuel($idopystation, $pump); 
        $monitor = array('pumpStatus'=>$pumpStatus, 'finish'=>$finish);
        (null != $this->logger)?$this->logger->debug('monitor refuel: '.json_encode($monitor)):null;
        return $monitor;
    }
    
    public function collectRefuel($idopystation, $pump, $fueltype)
    {
        $stationService = $this->getStationService($idopystation);
        
//         if($userData == null)
            $userData = $this->user;

        //print_r($this->userPreferences);
        
        $data = array('pump'=> $pump,
                    'fueltype'=> $fueltype,
                    'clientCode'=>'OPY_'.$userData['code_user'],
                    'email'=> $userData['username'],
                    'phoneNumber'=> $userData['phone_number'],
                    'invDocument'=> $this->userPreferences->inv_document,
                    'invName'=> $this->userPreferences->inv_name,
                    'invAddress'=> $this->userPreferences->inv_address,
                    'invPostalCode'=> $this->userPreferences->inv_postal_code,
                    'invLocality'=> $this->userPreferences->inv_locality                    
        );   
//         print_r($data);
        $collect = $stationService->collectRefuel($data);
//         print_r($collect);
//         die;
        if(isset($collect['status']))
        if($collect['status'] < 200 || $collect['status'] >= 300)
            throw new DomainException('Nothing to collect', 500);
        
        $adapter = $this->getAdapter($idopystation);
        $collect = $adapter->exchangeCollectArray($collect);
        
        return $collect;
    }
    
    public function finishRefuel($idopystation, $pump)
    {
        $stationService = $this->getStationService($idopystation);
        $finish = $stationService->finishRefuel($idopystation, $pump);
        (null != $this->logger)?$this->logger->debug('finish RefuelService: '.json_encode($finish)):null;
        return $finish;
    }
    
    
    
    
	/**
     * @return the $idopystation
     */
    public function getStationService($idopystation)
    {
        if(null != $this->stationService)
            return $this->stationService;
        else
            $this->stationService = $this->setStationService($idopystation);
        
        return $this->stationService;
    }

	/**
     * @param field_type $idopystation
     */
    public function setStationService($idopystation)
    {
        $stationInfo = $this->options->getPosNetwork()['opy_'.$idopystation];
        $stationService = "Opypos\\Service\\Pos\\".ucfirst($stationInfo['adapter'])."Service";
        $stationOptions = $this->options;
        $stationService = new $stationService($this->apicaller, $stationInfo, $stationOptions);
        
        return $stationService;
    }
    
    private function getAdapter($id)
    {
        $network = $this->options->getPosNetwork();
        $adapter = $network['opy_'.$id]['adapter'];
        return $this->getServiceLocator()->get($adapter);
    }

    
   
    
}
