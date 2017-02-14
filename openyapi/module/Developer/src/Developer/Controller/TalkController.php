<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Developer for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Developer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Socket\Raw;
use Ratchet\Server\IoServer;
use React\EventLoop\StreamSelectLoop;
use React\Socket\Server;
use Ratchet\App;
use MyApp\Chat;
use React\ZMQ\Context;
use React\EventLoop\Factory;

class TalkController extends AbstractActionController
{
    public function index2Action()
    {
        
               
        
        
        $factory = new Raw\Factory();
        
        $socket = $factory->createClient('127.0.0.1:8085');
//         $socket = $factory->createServer('tcp://cursozf2.local:8086');
        echo 'Connected to ' . $socket->getPeerName() . PHP_EOL;
        echo 'Connected to ' . $socket->getSockName() . PHP_EOL;
        
        // send simple HTTP request to remote side
//         $socket->send("GET / HTTP/1.1\r\n\Host: http://cursozf2.local\r\n\r\n");
//         $data = "GET / HTTP/1.1\r\nHost: www.google.com\r\n\r\n";
        $data = "Hello Weorld";
//         $data ="conn.send('Hello World!');";
        $ret = $socket->write($data);
//         $socket->send(json_encode($entryData));
        // receive and dump HTTP response
//         var_dump($socket->read(8192));
        
//         \Zend\Debug\Debug::dump($socket->read());
        \Zend\Debug\Debug::dump($ret);
        $socket->close();
        echo 'Connection closed'. PHP_EOL;
        
    }
    
    public function indexAction()
    {
        
        $context = new \ZMQContext();
        
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:9999");
        
        $socket->send(json_encode("asdasdas"));
        
    }
    
  

   
}
