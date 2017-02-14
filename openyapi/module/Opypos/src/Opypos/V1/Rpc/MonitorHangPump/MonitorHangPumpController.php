<?php
namespace Opypos\V1\Rpc\MonitorHangPump;

use DomainException;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\JsonModel;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Message;
use Zend\Http\Request;
use Zend\Db\Sql\Select;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class MonitorHangPumpController extends AbstractActionController
{
    protected $options;
    protected $options2;
    protected $apicaller;
    protected $adapterSlave;
    protected $stationService;
    protected $collectMapper;
    protected $logger = null;    
    
    public function __construct($options, $options2, $stationService, $apicaller, $adapterSlave, $collectMapper)
    {
        $this->options        = $options;
        $this->options2       = $options2;
        $this->stationService = $stationService;
        $this->apicaller      = $apicaller;
        $this->adapterSlave   = $adapterSlave;
        $this->collectMapper = $collectMapper;
        
        if($_SERVER['APPLICATION_ENV'] =='development')
        {
            // TODO remove log and set in a event
            $this->logger = new Logger;
            $this->logger->addWriter(new Stream('data/logs/refuellog.log'));
        }
        
    }
    
    public function monitorHangPumpAction()
    {
//         $this->sendEmailNotification('this_start', '@gmail.com');
        $idopystation = $this->params()->fromRoute('idopystation');
        if(empty($idopystation))
            throw new \Exception ("Not valid station.", 404);
    
        $pump = $this->params()->fromRoute('pump');
        if(empty($pump))
            throw new \Exception ("Not pummp selected.", 404);
        
        $fueltype = $this->params()->fromRoute('fueltype');
        if(empty($fueltype))
            throw new \Exception ("Not fueltype selected.", 404);
        
        $order = $this->params()->fromRoute('idorder');
        if(empty($order))
            throw new \Exception ("Not order selected.", 404);
        
        $request = $this->request;
        $headers = $request->getHeaders();
        $authentication = $headers->get('Authorization')
                                  ->getFieldValue();
        $authentication = explode('Bearer ',$authentication);
        
        if(isset($authentication[1]))
            $authentication = $authentication[1];
        else
            $authentication = false;
    
        $pumpStatus = $this->stationService->monitorHangPump($idopystation, $pump);
        (null != $this->logger)?$this->logger->debug('pumpStatus: '.json_encode($pumpStatus)):null;
//         ----------
        $data = new \stdClass();
        $data->idoffstation = $this->getOffStation($idopystation);
        $data->pump = $pump;
        $data->fueltype = $fueltype;
        $data->idorder = $order;
        
        (null != $this->logger)?$this->logger->debug('data: '.json_encode($data)):null;
        
        $pumpCollect = $this->collectMapper->insert($data);
        (null != $this->logger)?$this->logger->debug('autoCollect: '.json_encode($pumpCollect)):null;
//         -----------
        if($pumpStatus['pumpStatus']['pumpHanged']==="true")
        {
            $this->notifyApp($idopystation, $pump, $pumpStatus, $order, $authentication, $pumpCollect);
            
            (null != $this->logger)?$this->logger->debug('done TRUE hang: '.$order):null;
            (null != $this->logger)?$this->logger->info('--------- Refuel END ---------'):null;
        }
        elseif($pumpStatus['pumpStatus']['pumpHanged']==="false")
        {
            $this->notifyApp($idopystation, $pump, $pumpStatus, $order, $authentication, $pumpCollect);
            
            (null != $this->logger)?$this->logger->debug('done FALSE hang: '.$order):null;
            (null != $this->logger)?$this->logger->debug('Hang Timeout'):null;
            (null != $this->logger)?$this->logger->info('--------- Refuel END ---------'):null;
        }
        
        //$this->sendEmailNotification('done hang: '.$order, '@gmail.com');
    
        return new JsonModel($pumpStatus);
    
    }
    
    private function sendEmailNotification($data, $to)
    {
        $transport = new SmtpTransport();
        $message = new Message();
        $message->addTo($to)
                ->addFrom('openy@.com', 'Openy App')
                ->setSubject('Pump hanged')
                ->setBody(json_encode($data));
    
        $options = new SmtpOptions($this->options2->getSmtpOptions());
    
        $transport->setOptions($options);
        $result = $transport->send($message);
    
    }
    
    private function notifyApp($idopystation, $pump, $pumpStatus, $order, $authentication, $pumpCollect)
    {
        $ar = array('idorder'=>$order,
            'idopystation'=>$idopystation,
            'pump'=>$pump,
            'pumpHanged' => $pumpStatus['pumpStatus']['pumpHanged'],
            'timestamp' => $pumpStatus['pumpStatus']['timestamp'],
            'collect' => $pumpCollect,
        );
    
        $registrationAppId = $this->getRegistrationIdByBearer($authentication);
        $registrationIds = array($registrationAppId);
      
        $title = 'Enhorabuena, repostaje con Openy finalizado!';
        $message = 'Ticket de Orden '.$order.' enviado!';
        
        $msg = array(
            'data'       => $ar,
            'title'         => $title,
            'message'       => $message,
            //'subtitle'      => 'This is a subtitle. subtitle',
            //'tickerText'    => 'Ticker text here...Ticker text here...Ticker text here',
            'vibrate'   => 1,
            'sound'     => 1
        );
        $data = array(
            'registration_ids'  => $registrationIds,
            'data'              => $msg
        );
    
        (null != $this->logger)?$this->logger->debug(json_encode($data)):null;
        $this->sendPushNotification($data); 
//         $this->sendEmailNotification(json_encode($ar), '@gmail.com');
//         (null != $this->logger)?$this->logger->debug(json_encode($ar)):null;
    }
    
    
    private function getRegistrationIdByBearer($accessToken)
    {
        $select = new Select('oauth_access_tokens');
        $select->columns(array( 'access_token', 'client_id', 'user_id'));
        $select->join('app_register', 'app_register.privatekey = oauth_access_tokens.client_id',
                        array('osversion', 'registerid'),
                        'left');
        $select->where(array('oauth_access_tokens.access_token' => $accessToken));
        //$sql = $select->getSqlString();
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
    
        $data = $driverResult->current();
        
        return $data['registerid'];
    }
    
    private function sendPushNotification($data, $device = 'Android')
    {
        if($device == 'Android')
        {
            $pusher = $this->options->getAndroidPush();
            $this->apicaller->setHeaders($pusher['headers']);
            $url = sprintf($pusher['endpoint']);
            $value = $this->apicaller->getResponse($url, $data, Request::METHOD_POST);
			(null != $this->logger)?$this->logger->debug('GOOGLE Response:'. json_encode($value)):null;
        }
        elseif($device == 'iOS')
        {
            $this->apicaller->setHeaders($pusher['headers']);
            $url = sprintf($pusher['endpoint']);
            $value = $this->apicaller->getResponse($url, $data, Request::METHOD_POST);
        }
    }
    
    private function getOffStation($idopystation)
    {
        $select = new Select('opy_station');
        $select->where(array('idstation' => $idopystation));
        //var_dump($select->getSqlString());
    
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
    
        if (0 === count($driverResult)) {
            throw new DomainException('Not opy station set', 404);
        }
    
        return $driverResult->current()['idoffstation'];
    }
}
