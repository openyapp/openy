<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use RuntimeException;

// use React\ZMQ\Context;
use React\EventLoop\Factory;

class MonitorController extends AbstractActionController
{   
//     protected $eventIdentifier = 'NoSqliteauth';
    
    public function indexAction()
    {
        $layout = $this->layout();
        $layout->setTemplate('layout/layout');
        return new ViewModel();
    }
    
    public function testraAction()
    {
        
//             echo "kaka";
            $entryData = array(
                'category' => 'kittensCategory',
                'title'    => 'title',
                'article'  => 'article',
                'when'     => time()
            );
        
            $context = new \ZMQContext();
            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://localhost:5555");
//             socket->connect("tcp://10.8.0.1:5555");
        
            $socket->send(json_encode($entryData));
        
            print_r(json_encode($entryData));
        
        
            return new ViewModel();
         
    }
    
    public function posMonitorAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setVariables(array('key' => 'value'));
        return $viewModel;
    }
    
    
    public function statusAction()
    {        
        $viewModel = new ViewModel();
        $viewModel->setVariables(array('key' => 'value'));
        return $viewModel;
    }
    
    public function clientAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setVariables(array('key' => 'value'));
        return $viewModel;
    }
    
    public function testserverAction()
    {
        
//         ini_set('display_errors', 1);
//         error_reporting(E_ALL);
        $file = $_SERVER['DOCUMENT_ROOT'].'/wss/client/lib/class.websocket_client.php';
        echo $file;
        require_once ($file);
        
        $clients = array();
        $testClients = 30;
        $testMessages = 500;
        for($i = 0; $i < $testClients; $i++)
        {
            $clients[$i] = new \WebsocketClient;
            $clients[$i]->connect('127.0.0.1', 8000, '/demo', 'http:///');
        }
        usleep(5000);
        
        $payload = json_encode(array(
            'action' => 'echo',
            'data' => 'dos'
        ));
        
        for($i = 0; $i < $testMessages; $i++)
        {
            $clientId = rand(0, $testClients-1);
            $clients[$clientId]->sendData($payload);
            usleep(5000);
        }
        usleep(5000);        
    }
    
}
