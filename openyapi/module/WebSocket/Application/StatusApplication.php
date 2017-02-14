<?php
namespace WebSocket\Application;

/**
 * Shiny WSS Status Application
 * Provides live server infos/messages to client/browser.
 * 
 * @author Simon Samtleben <web@lemmingzshadow.net>
 */
class StatusApplication extends Application
{
    private $_clients = array();
	private $_serverClients = array();
	private $_serverInfo = array();
	private $_serverClientCount = 0;


	public function onConnect($client)
    {
        echo "S1-";
		$id = $client->getClientId();
        $this->_clients[$id] = $client;
		$this->_sendServerinfo($client);
    }

    public function onDisconnect($client)
    {
        echo "S2-";
        $id = $client->getClientId();		
		unset($this->_clients[$id]);     
    }

    public function onData($data, $client)
    {		
        echo "S3-";
        // currently not in use...
    }
	
	public function setServerInfo($serverInfo)
	{
	    echo "S4-";
		if(is_array($serverInfo))
		{
			$this->_serverInfo = $serverInfo;
			return true;
		}
		return false;
	}


	public function clientConnected($ip, $port)
	{
	    echo "S5-";
		$this->_serverClients[$port] = $ip;
		$this->_serverClientCount++;
		$this->statusMsg('Client connected: ' .$ip.':'.$port);
		$data = array(
			'ip' => $ip,
			'port' => $port,
			'clientCount' => $this->_serverClientCount,
		);
		$encodedData = $this->_encodeData('clientConnected', $data);
		$this->_sendAll($encodedData);
	}
	
	public function clientDisconnected($ip, $port)
	{
	    echo "S6-";
		if(!isset($this->_serverClients[$port]))
		{
			return false;
		}
		unset($this->_serverClients[$port]);
		$this->_serverClientCount--;
		$this->statusMsg('Client disconnected: ' .$ip.':'.$port);
		$data = array(			
			'port' => $port,
			'clientCount' => $this->_serverClientCount,
		);
		$encodedData = $this->_encodeData('clientDisconnected', $data);
		$this->_sendAll($encodedData);
	}
	
	public function clientActivity($port)
	{
	    echo "S7-";
		$encodedData = $this->_encodeData('clientActivity', $port);
		$this->_sendAll($encodedData);
	}

	public function statusMsg($text, $type = 'info')
	{		
	    echo "S8-";
		$data = array(
			'type' => $type,
			'text' => '['. strftime('%m-%d %H:%M', time()) . '] ' . $text,
		);
		$encodedData = $this->_encodeData('statusMsg', $data);		
		$this->_sendAll($encodedData);
	}
	
	private function _sendServerinfo($client)
	{
	    echo "S9-";
		if(count($this->_clients) < 1)
		{
			return false;
		}
		$currentServerInfo = $this->_serverInfo;
		$currentServerInfo['clientCount'] = count($this->_serverClients);
		$currentServerInfo['clients'] = $this->_serverClients;
		$encodedData = $this->_encodeData('serverInfo', $currentServerInfo);
		$client->send($encodedData);
	}
	
	private function _sendAll($encodedData)
	{		
	    echo "S10-";
		if(count($this->_clients) < 1)
		{
			return false;
		}
		foreach($this->_clients as $sendto)
		{
            $sendto->send($encodedData);
        }
	}
}