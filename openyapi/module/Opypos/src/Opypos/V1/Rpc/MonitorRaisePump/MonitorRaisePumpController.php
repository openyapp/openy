<?php
namespace Opypos\V1\Rpc\MonitorRaisePump;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\JsonModel;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Message;
use Zend\Http\Request;
use Zend\Db\Sql\Select;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class MonitorRaisePumpController extends AbstractActionController
{
    protected $options;
    protected $options2;
    protected $apicaller;
    protected $stationService;
    protected $adapterSlave;
    protected $logger = null;
    
    
    public function __construct($options, $options2, $stationService, $apicaller, $adapterSlave)
    {
        $this->options        = $options;
        $this->options2       = $options2;
        $this->stationService = $stationService;
        $this->apicaller      = $apicaller;
        $this->adapterSlave   = $adapterSlave;
        
        if($_SERVER['APPLICATION_ENV'] =='development')
        {
            // TODO remove log and set in a event
            $this->logger = new Logger;
            $this->logger->addWriter(new Stream('data/logs/refuellog.log'));
        }
        
    }
    
    public function monitorRaisePumpAction()
    {
        //$this->sendEmailNotification('this_start', '@gmail.com');
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
        
        $pumpStatus = $this->stationService->monitorRaisePump($idopystation, $pump);
        (null != $this->logger)?$this->logger->debug('pump status: '.json_encode($pumpStatus)):null;
        
        if($pumpStatus['pumpRaised']==="true")
        {
            $this->notifyApp($idopystation, $pump, $pumpStatus, $order, $authentication);
            
            // Start counter
            $monitor_url = $this->options2->getMonitorsUrl();
            $monitors = $this->options2->getRefuelMonitors();
            
            $monitorHang = $monitors['hangpump'];
            //$composeCurl = 'curl -i -H "Accept: application/json" -H "Content-Type: '.$monitorHang['headers']['Content-Type'].'" -H "Authorization: Bearer '.$authentication.'" '.$monitor_url.$monitorHang['endpoint'].'/'.$idopystation.'/'.$pump.'/'.$fueltype.'/'.$order.' -k > /dev/null 2>/dev/null &';
            $composeCurl = 'curl -i -H "Accept: application/json" -H "Content-Type: '.$monitorHang['headers']['Content-Type'].'" -H "Authorization: Bearer '.$authentication.'" '.$monitor_url.$monitorHang['endpoint'].'/'.$idopystation.'/'.$pump.'/'.$fueltype.'/'.$order.' > /dev/null 2>/dev/null &';
			
			(null != $this->logger)?$this->logger->debug('Curl Call Hang: '.$order.': '.json_encode($composeCurl)):null;
            $salida = exec($composeCurl);
            
            (null != $this->logger)?$this->logger->debug('done TRUE raise: '.$order.': '.json_encode($pumpStatus)):null;
            //$this->sendEmailNotification('done raise: '.$order, '@gmail.com');
            
            return new JsonModel($pumpStatus);
        }
        elseif($pumpStatus['pumpRaised']==="false")
        {
            $pumpStatus = $this->stationService->cancellOrderTimeoutRaise($idopystation, $pump, $order);
            
            $this->notifyApp($idopystation, $pump, $pumpStatus, $order, $authentication);
            (null != $this->logger)?$this->logger->debug('done FALSE raise: '.$order.': '.json_encode($pumpStatus)):null;
            (null != $this->logger)?$this->logger->info('--------- Refuel END ---------'):null;
            return new JsonModel($pumpStatus);
        }
        
        
    }
    
    private function sendEmailNotification($data, $to)
    {
        $transport = new SmtpTransport();
        $message = new Message();
        $message->addTo($to)
                ->addFrom('openy@.com', 'Openy App')
                ->setSubject('Pump raised')
                ->setBody(json_encode($data));
    
        $options = new SmtpOptions($this->options2->getSmtpOptions());
    
        $transport->setOptions($options);
        $result = $transport->send($message);
    
    }
    
    private function notifyApp($idopystation, $pump, $pumpStatus, $order, $authentication)
    {
        $registrationAppId = $this->getRegistrationIdByBearer($authentication);
        $registrationIds = array($registrationAppId);

// 		$title = 'Openy!';
//     	$message = 'Gracias por preferirnos';


        if(isset($pumpStatus['pumpRaised']))
		{
	    	switch($pumpStatus['pumpRaised'])
        	{
            	case 'true':
                	$title = 'Has iniciado un repostaje con Openy!';
                	$message = 'Orden: '.$order;
            	break;
            	case 'false':
                	$title = 'Tiempo agotado!';
                	$message = 'La Orden '.$order.' ha sido cancelada!';
            	break;            	
        	}
        	$ar = array('idorder'=>$order, 
                    'idopystation'=>$idopystation, 
                    'pump'=>$pump, 
                    'pumpRaised' => $pumpStatus['pumpRaised'],
                    'timestamp' => $pumpStatus['timestamp']
        	);
        }
        else
        {
            $title = 'Tiempo agotado!';
            $message = 'La Orden '.$order.' ha sido cancelada!';
            $ar = array(
                'idorder'=>$order,
                'idopystation'=>$idopystation,
                'pump'=>$pump,
                'pumpRaised' => 'false'                
            );
        }
            
        $msg = array(
            'data'          => $ar,
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
        return;
    }
    
    
    private function getRegistrationIdByBearer($accessToken)
    {   
        $select = new Select('oauth_access_tokens');
        $select->columns(array( 'access_token', 'client_id', 'user_id'));
        $select->join('app_register', 'app_register.privatekey = oauth_access_tokens.client_id',
                    array('osversion', 'registerid'),
                    'left');
        $select->where(array('oauth_access_tokens.access_token' => $accessToken));
        
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
    
    
}
