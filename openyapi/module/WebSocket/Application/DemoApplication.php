<?php

namespace WebSocket\Application;

/**
 * Websocket-Server demo and test application.
 * 
 * @author Simon Samtleben <web@lemmingzshadow.net>
 */
class DemoApplication extends Application
{
    private $_clients = array();
	private $_filename = '';

	public function onConnect($client)
    {
        echo "A1-";
		$id = $client->getClientId();
        $this->_clients[$id] = $client;	

        
    }

    private function _sendFileContent($client)
    {
        echo "A2-";
//         $data = file_get_contents(substr(__FILE__, 0, strpos(__FILE__, 'server')).'tmp/fileme.md');
//         $data = file_get_contents("http://your.server.com:60606/trabajo/ESTADO.DAT");

        $data = file_get_contents("http://192.168.1.134:60606/trabajo/ESTADO.DAT"); 
        $currentFileContent = array();
        $currentFileContent['content'] = $data;
        $encodedData = $this->_encodeData('fileContent', $currentFileContent);
        $client->send($encodedData);
    }
    
    public function onDisconnect($client)
    {
        echo "A3-";
        $id = $client->getClientId();		
		unset($this->_clients[$id]);     
    }

    public function onData($data, $client)
    {		
        //echo "A4-";
        $decodedData = $this->_decodeData($data);		
		if($decodedData === false)
		{
			// @todo: invalid request trigger error...
		}
		
		$actionName = '_action' . ucfirst($decodedData['action']);		
		if(method_exists($this, $actionName))
		{			
			call_user_func(array($this, $actionName), $decodedData['data'], $client);
		}
    }
	
	public function onBinaryData($data, $client)
	{		
	    //echo "A5-";
		$filePath = substr(__FILE__, 0, strpos(__FILE__, 'server')) . 'tmp/';
		$putfileResult = false;
		if(!empty($this->_filename))
		{
			$putfileResult = file_put_contents($filePath.$this->_filename, $data);			
		}		
		if($putfileResult !== false)
		{
			
			$msg = 'File received. Saved: ' . $this->_filename;
		}
		else
		{
			$msg = 'Error receiving file.';
		}
		$client->send($this->_encodeData('echo', $msg));
		$this->_filename = '';
	}
	
	private function _actionEcho($text, $client=null)
	{		
	    //echo "A6-";
	    $encodedData = $this->_encodeData('echo', $text);		
		foreach($this->_clients as $sendto)
		{
			$sendto->send($encodedData);
        }
	}
	
	private function _actionFilecontent($text,  $client=null)
	{
// 	    echo "A7-";
// 	    $this->_sendFileContent($client);
// 	    echo "kaka file ";
// 	    $data = file_get_contents(substr(__FILE__, 0, strpos(__FILE__, 'server')).'tmp/fileme.md');
// 	    echo $data;
// 	    $encodedData = $this->_encodeData('file', $data);
// 	    foreach($this->_clients as $sendto)
// 	    {
// 	        $sendto->send($encodedData);
// 	    }
	    $i=1;
	    while($i==1)
	    {
// 	        $data = file_get_contents(substr(__FILE__, 0, strpos(__FILE__, 'server')).'tmp/fileme.md');
// 	        $encodedData = $this->_encodeData('file', $data);
// 	        sleep(3);
	        usleep(0100000);
// 	        $sendto->send($encodedData);
	        $this->_sendFileContent($client);
// 	        echo time()."\n";
	    }
	}
	
	private function _actionSetFilename($filename)
	{		
	    echo "A8-";
		if(strpos($filename, '\\') !== false)
		{
			$filename = substr($filename, strrpos($filename, '\\')+1);
		}
		elseif(strpos($filename, '/') !== false)
		{
			$filename = substr($filename, strrpos($filename, '/')+1);
		}		
		if(!empty($filename)) 
		{
			$this->_filename = $filename;
			return true;
		}
		return false;
	}
}
