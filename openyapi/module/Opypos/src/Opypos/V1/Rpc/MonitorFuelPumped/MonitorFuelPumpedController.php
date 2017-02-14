<?php
namespace Opypos\V1\Rpc\MonitorFuelPumped;

use Zend\Mvc\Controller\AbstractActionController;

class MonitorFuelPumpedController extends AbstractActionController
{
    protected $options;
    protected $options2;
    //     protected $apicaller;
    //     protected $adapterSlave;
    protected $stationService;
    
    public function __construct($options, $options2, $stationService)
    {
        $this->options          = $options;
        $this->options2          = $options2;
        $this->stationService = $stationService;
    }
    
    public function monitorFuelPumpedAction()
    {
        $this->sendEmailNotification('this_start', '@gmail.com');
        $idopystation = $this->params()->fromRoute('idopystation');
        if(empty($idopystation))
            throw new \Exception ("Not valid station.", 404);
    
        $pump = $this->params()->fromRoute('pump');
        if(empty($pump))
            throw new \Exception ("Not pummp selected.", 404);
    
        $pumpStatus = $this->stationService->monitorRaisePump($idopystation, $pump);
        $this->sendEmailNotification($pumpStatus, '@gmail.com');
    
    
        return new JsonModel($pumpStatus);
    
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
}
