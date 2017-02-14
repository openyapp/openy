<?php
namespace Opypos\Service\Pos;

// use Zend\Db\Sql\Update;
// use Zend\Db\Sql\Insert;
// use Zend\Db\Sql\Select;
// use ZF\ApiProblem\ApiProblemResponse;
// use Openy\Exception;

use Zend\Http\Request;
// use Zend\Mail\Transport\Smtp as SmtpTransport;
// use Zend\Mail\Transport\SmtpOptions;
// use Zend\Mail\Message;
// use Zend\View\Model\ViewModel;
// use Zend\View\Renderer\PhpRenderer;
// use Zend\View\Resolver;

// use Zend\Mime\Message as MimeMessage;
// use Zend\Mime\Part as MimePart;

// use Zend\ServiceManager\ServiceLocatorAwareInterface;
// use Zend\ServiceManager\ServiceLocatorAwareTrait;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class aadapterService
{
    private $apicaller;
    private $stationInfo;
    private $options;
    
    const RAISE_PUMP = '89';
    const FUEL_PUMPED = '';
    const HANG_PUMP = '8A';
    const BLOCKED_PUMP = '84';
    
    protected $logger = null;
    
    public function __construct($apicaller, $stationInfo, $stationOptions)
    {
        $this->apicaller = $apicaller;      
        $this->stationInfo = $stationInfo; 
        $this->options = $stationOptions;
        
        $this->logger = new Logger;
        $this->logger->addWriter(new Stream('data/logs/refuellog.log'));
        
        
    }
    
    public function blockPump($pump)
    {
        $data = array(
            'command'=>'BLOCK_PUMP',
            'pump'=>$pump            
        );
    
        $value = $this->sendCommand($data);
        return $value;
    }
    
    public function setPrice($data)
    {
        $data = array(
            'command'=>'SET_PRICE_PER_PRODUCT',
            'product'=>$this->getProductConversor()[$data->fueltype],
            'price'=>$data->price,
            'pump'=>$data->pump
        );
        $value = $this->sendCommand($data);
        return $value;
    }
    
    public function presetAmount($data)
    {
        $data = array(
            'command'=>'PRESET_AMOUNT',
            'pump'=>$data->pump,
            'product'=>$this->getProductConversor()[$data->fueltype],
            'price'=>$data->price,
            'amount'=>$data->toRefuel
        );
        $value = $this->sendCommand($data);
        return $value;
    }
    
    public function collectSupply($pump)
    {
        $data = array(
            'command'=>'COLLECT_SUPPLY',
            'pump'=>$pump,
            //'product'=>$this->getProductConversor()[$data->fueltype],
            //'price'=>$data->price,
            //'amount'=>$data->amount
        );
        $value = $this->sendCommand($data);
        return $value;
    }
    
    public function cancellSupply($idopystation, $pump)
    {
        $data = array(
            'command'=>'BLOCK_PUMP',
            'pump'=>$pump            
        );
        $value = $this->sendCommand($data);
        return $value;
    }
    
    public function finishRefuel($idopystation, $pump)
    {
        $blockPump = $this->blockPump($pump);
        (null != $this->logger)?$this->logger->debug('finish blockPump: '.json_encode($blockPump)):null;
        $collectSupply = $this->collectSupply($pump);
        (null != $this->logger)?$this->logger->debug('finish collectSupply: '.json_encode($collectSupply)):null;
        
        return array ('finish'=> true, 'blockPump'=> $blockPump, 'collectSupply'=>$collectSupply);       
    }
    
//     {
//         "idopystation": "1",
//         "pump":"3",
//         "fueltype": "G95",
//         "amount": "70",
//         "price":"1.889",
//         "email":"@gmail.com",
//         "antifraudPin":"5310",
//         "userPin":"1234"
//     }
    
    public function collectRefuel($data)
    {
        
        $data['fueltype'] = $this->getProductConversor()[$data['fueltype']];
        $data = array(          
                "pump"=>$data['pump'], 
                "product"=>$data['fueltype'], 
                "email"=>$data['email'],
                  "codigocliente"=>$data['clientCode'],
                  "nif"=>$data['invDocument'],
                  "nombre"=>$data['invName'],
                  "direccion"=>$data['invAddress'],
                  "codigopostal"=>$data['invPostalCode'],
                  "poblacion"=>$data['invLocality'],
                  "telefono1"=>$data['phoneNumber']          
        );
        //print_r($data);
        
        $resource = $this->stationInfo['resources']['collect'];
        $this->apicaller->setHeaders($resource['headers']);
        $url = sprintf($this->stationInfo['endpoint'].$resource['endpoint']);
        $value = $this->apicaller->getResponseMute($url,  $data, Request::METHOD_POST);
        return $value;
        
    }
    
    
    
    private function sendCommand($data)
    {
        (null != $this->logger)?$this->logger->debug("Send: ".json_encode($data)):null;
        $resource = $this->stationInfo['resources']['sendorder'];
        $this->apicaller->setHeaders($resource['headers']);
        $url = sprintf($this->stationInfo['endpoint'].$resource['endpoint']);
        $value = $this->apicaller->getResponse($url,  $data, Request::METHOD_POST);
        return $value;
    }   
    
    private function getProductConversor()
    {
        $configuration = $this->getConfiguration();
        return array_flip($configuration['product_conversor']);
    }
    
    private function getConfiguration()
    {
        $resource = $this->stationInfo['resources']['configuration'];
        $this->apicaller->setHeaders($resource['headers']);
        $url = sprintf($this->stationInfo['endpoint'].$resource['endpoint']);
        $value = $this->apicaller->getResponse($url);
        return $value['response']['_embedded']['configuration'][0];        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    private function getPumpStatus($filename, $pump = null)
    {
    
        $content = file_get_contents($filename);
        $content = explode("\n",$content);
        //print_r($content);
    
        unset($content[sizeof($content)-1]);    // last CR/LF line
    
        $estado = array('No de Computador (2)'=> 2,
            'No de Manguera (2)'=> 2,
            'Codigo producto (2)'=> 2,
            'Numero Suministro (6)'=> 6,
            'Precio Venta (6)'=> 6,
            'Importe (8)'=> 8,
            'Litros (6)'=> 6,
            'Predeterminacion Pts (8)'=> 8,
            'Predeterminacion Lit (6)'=> 6,
            'Codigo A1 (2)'=> 2,
            'Importe A1 (8)'=> 8,
            'Litros A1 (6)'=> 6,
            'Codigo A2 (2)'=> 2,
            'Importe A2 (8)'=> 8,
            'Litros A2 (6)'=> 6,
            'Estado Surtidor (2)'=> 2,
            'Error (Libre) (2)'=> 2,
            'Error SCP-II - HCP-II (2)'=> 2,
            'Error Surtidor (2)'=> 2,
            'CR (13) (1)'=> 1,
            'LF (10) (1)'=> 1
        );
    
        $keys=array('No de Computador (2)',
            'Codigo producto (2)',
            'Numero Suministro (6)',
            'Importe A2 (8)',
            'Litros A2 (6)',
            'Estado Surtidor (2)'
        );
        //\Zend\Debug\Debug::dump($estado, "Estado: ");
        $pumpStatus = array();
        foreach($content as $surtidor)
        {
            $prev = 0;
            $valestado = array();
            foreach($estado as $key => $value)
            {
                for($a=0;$a<$value;$a++)
                {
                if(in_array($key, $keys))
                    @$valestado[$key].=$surtidor[$prev];
                    $prev++;
                }
                }
                $pumpStatus[]=$valestado;
    
        }
    
    
        if($pump)
            return $pumpStatus[$pump];
            else
                return $pumpStatus;
    
    }
    
    
    
    
    
    public function monitorRaisePump($idopystation, $pump)
    {
        $filename = $this->stationInfo['endpoint'].$this->stationInfo['files']['estados'];
        $pumpStatus = $this->getPumpStatus($filename, $pump);
    
        (null != $this->logger)?$this->logger->debug(json_encode("aadapter monitorRaisePump start")):null;
        
        $time_start = microtime(true);
        $i=1;
        while($i==1)
        {
            $pumpStatus = $this->getPumpStatus($filename, $pump);
            if($pumpStatus['Estado Surtidor (2)'] == aadapterService::RAISE_PUMP)
                break;
    
            //usleep(2000000); // 2 secs
            usleep(100000); // 0.1 secs
            $time_current = microtime(true);
            if($time_current - $time_start > $this->options->getRaisePumpTimeout())
            {
                (null != $this->logger)?$this->logger->debug(json_encode($time_current. ' - '.$time_start)):null;
                break;
            }
                
        }
        
        
        
        if($pumpStatus['Estado Surtidor (2)'] == aadapterService::RAISE_PUMP)
        {
            $status = array(
                'timestamp' => date('Y-m-d G:i:s'),
                'pumpRaised' => 'true',
                'status'=>$pumpStatus
            );
        }
        else
        {
            $status = array(
                'timestamp' => date('Y-m-d G:i:s'),
                'pumpRaised' => 'false',
                'status'=>$pumpStatus
            );                         
        }
        //(null != $this->logger)?$this->logger->debug(json_encode($status)):null;
        return $status;
            
    }
    
    public function monitorHangPump($idopystation, $pump)
    {
        (null != $this->logger)?$this->logger->debug(json_encode("aadapter monitorHangPump start")):null;
        
//         $stationInfo = $this->options->getPosNetwork()['opy_'.$idopystation];
        $filename = $this->stationInfo['endpoint'].$this->stationInfo['files']['estados'];
        $pumpStatus = $this->getPumpStatus($filename, $pump);
    
        $time_start = microtime(true);
			$i=1;
        while($i==1)
        {
            $pumpStatus = $this->getPumpStatus($filename, $pump);
//(null != $this->logger)?$this->logger->debug('pumpStatus: '. json_encode($pumpStatus)):null;
            if($pumpStatus['Estado Surtidor (2)'] == aadapterService::HANG_PUMP)
                break;
            elseif($pumpStatus['Estado Surtidor (2)'] == aadapterService::BLOCKED_PUMP)
                break;
    
            //usleep(2000000); // 2 secs
            usleep(100000); // 0.1 secs
            $time_current = microtime(true);
//(null != $this->logger)?$this->logger->debug('time_start: '. json_encode($time_start)):null;
//(null != $this->logger)?$this->logger->debug('time_current: '. json_encode($time_current)):null;
            if(($time_current - $time_start) > $this->options->getFinishRefuel() )
                break;
        }
        //(null != $this->logger)?$this->logger->debug(aadapterService::HANG_PUMP):null;
        (null != $this->logger)?$this->logger->debug("estado: ".json_encode($pumpStatus['Estado Surtidor (2)'])):null;
        
        if($pumpStatus['Estado Surtidor (2)'] == aadapterService::HANG_PUMP)
            return array(
                'timestamp' => date('Y-m-d G:i:s'),
                'pumpHanged' => 'true',
                'status'=>$pumpStatus
            );
        elseif($pumpStatus['Estado Surtidor (2)'] == aadapterService::BLOCKED_PUMP)
            return array(
                'timestamp' => date('Y-m-d G:i:s'),
                'pumpHanged' => 'true',
                'status'=>$pumpStatus
            );
        else
            return array(
                'timestamp' => date('Y-m-d G:i:s'),
                'pumpHanged' => 'false',
                'status'=>$pumpStatus
            );
                
        
    }
    
    public function monitorFuelPumped($idopystation, $pump)
    {
        $stationService = $this->getStationService($idopystation);
        return $stationService->monitorFuelPumpued($idopystation, $pump);
    }
    
    
    
}
