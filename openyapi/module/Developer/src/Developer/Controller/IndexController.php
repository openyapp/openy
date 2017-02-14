<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Developer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use MessageBird\Client;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Message;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {        
        return new ViewModel();
    }
    
    public function testAction()
    {
        $i=1;
        
        $filename = "http://192.168.1.134:60606/trabajo/ESTADO.DAT";
        echo $filename;
        
        $time_start = microtime(true);
        
        echo "<pre>";
        print_r($time_start);
        echo "</pre>";
        
        $pumpStatus = $this->getPumpStatus($filename, 3);
        
        echo "<pre>";
        print_r($pumpStatus['Estado Surtidor (2)']);
        echo "</pre>";
        
        while($i==1)
        {
            $pumpStatus = $this->getPumpStatus($filename, 3);
            if($pumpStatus['Estado Surtidor (2)']=='85')
                break;
            
            usleep(2000000);
            $time_current = microtime(true);
            if($time_current - $time_start > 60 )
                break;
        }
        
        echo "<pre>";
        print_r($time_current);
        echo "</pre>";
        
        echo "<pre>";
        print_r($time_current - $time_start);
        echo "</pre>";
        
        echo "<pre>";
        print_r($pumpStatus['Estado Surtidor (2)']);
        echo "</pre>";
        
        echo "Manguera descolgada";
        
        
        
        $result=array();
        $view = new ViewModel(array('test' => $pumpStatus));
        $view->setTemplate('developer/index/alltest.phtml');
        return $view;
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
    
    
    public function garbageAction()
    {
        /**
         * HERE you test things
         */
        $garbage = 'kaka';

        
        $MessageBird = new Client('test_ARSVabwudlWy4Cw0lHocWN65B'); // Set your own API access key here.

        $Message             = new \MessageBird\Objects\Message();
        $Message->originator = 'MessageBird';
        $Message->recipients = array(34687780786);
        $Message->body = 'This is a test message.';
        
        try {
            $MessageResult = $MessageBird->messages->create($Message);
            var_dump($MessageResult);
        
        } catch (\MessageBird\Exceptions\AuthenticateException $e) {
            // That means that your accessKey is unknown
            echo 'wrong login';
        
        } catch (\MessageBird\Exceptions\BalanceException $e) {
            // That means that you are out of credits, so do something about it.
            echo 'no balance';
        
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        
        return new ViewModel(array('garbage' => $garbage));
        
    }
    
    public function sendWithGmailAction()
    {     
        $options   = new SmtpOptions(array(
            'host'              => 'smtp.gmail.com',
            'connection_class'  => 'login',
            'connection_config' => array(
                'ssl'       => 'ssl',
                'username' => 'appopeny@gmail.com',
                'password' => '&fdja2DDk22_'
            ),
            'port' => 465,
        ));        

        $transport = new SmtpTransport();
        $message = new Message();
        $message->addTo('@gmail.com')
                ->addFrom('appopeny@gmail.com')
                ->setSubject('Test Gmail SMTP')
                ->setBody('Testing Gmail SMTP');
        
        $options = new SmtpOptions($options);
        
        $transport->setOptions($options);    
        $result = $transport->send($message);
        
        $view = new ViewModel(array('test' => $result));
        $view->setTemplate('developer/index/alltest.phtml');
        return $view;

    }
    
    public function getSha1Action()
    {
        $text = '<Version Ds_Version="0.0"><Message><Monitor><Ds_MerchantCode>999008881</Ds_MerchantCode><Ds_Terminal>1</Ds_Terminal><Ds_Order>113847</Ds_Order> <Ds_Merchant_Data>datos</Ds_Merchant_Data></Monitor></Message></Version>';
        $sign = 'qwertyasdf0123456789';
        $sha = sha1($text.$sign);
        
        
        $Digest=sha1('<Version Ds_Version="0.0"><Message><Monitor><Ds_MerchantCode>999008881</Ds_MerchantCode><Ds_Terminal>1</Ds_Terminal><Ds_Order>113847</Ds_Order><Ds_Merchant_Data>datos</Ds_Merchant_Data></Monitor></Message></Version>qwertyasdf0123456789');
        echo $Digest;
        
        
        
        
        
        $view = new ViewModel(array('test' => $sha));
        $view->setTemplate('developer/index/alltest.phtml');
        return $view;
    }
}
