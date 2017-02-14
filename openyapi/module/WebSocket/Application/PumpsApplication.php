<?php
namespace WebSocket\Application;

/**
 * Shiny WSS Status Application
 * Provides live server infos/messages to client/browser.
 * 
 * @author Simon Samtleben <web@lemmingzshadow.net>
 */
class PumpsApplication extends Application
{
    private $_clients = array();
	private $_serverClients = array();
	private $_serverInfo = array();
	private $_serverClientCount = 0;
	private $i=1;

	
	public function __construct()
	{
// 	    $composeCurl = 'curl -i -H "Accept: application/json" -H http:///wss/server/server.php > /dev/null 2>/dev/null &';
// 	    echo $composeCurl."---sss---";
// 	    die;
// 	    $salida = exec($composeCurl);
	}

    public function onConnect($client)
    {
        echo "A1-";
		$id = $client->getClientId();
        $this->_clients[$id] = $client;	
        $this->_actionFilecontent(null,  $client);
        
    }
    public function onDisconnect($client)
    {
        echo "0-";
        $id = $client->getClientId();
        unset($this->_clients[$id]);
        $this->i=0;
        exit(0);
    }
	
	
	private function _sendFileContent($client)
	{
	    //echo "A2-";
	    //         $data = file_get_contents(substr(__FILE__, 0, strpos(__FILE__, 'server')).'tmp/fileme.md');
	    //         $data = file_get_contents("http://your.server.com:60606/trabajo/ESTADO.DAT");
	
	    //$data = file_get_contents("http://192.168.1.134:60606/trabajo/ESTADO.DAT");
// 	    $data = file_get_contents("http://10.8.0.22:60606/trabajo/ESTADO.DAT");            // Production Mode
	    $data = file_get_contents("http://192.168.1.142:60606/trabajo/ESTADO.DAT");            // Local Mode
	    
	    $pumpStatus = $this->parseData($data);
	    $pumpStatusData = $this->imagePump($pumpStatus);
	    
	    
		$currentFileContent = array();
	    $currentFileContent['state'] = $pumpStatusData;
	    $encodedData = $this->_encodeData('state', $currentFileContent);
	    $client->send($encodedData);
	}
	
	private function imagePump($pumpStatus)
	{
	    $pumpImages = array();
	    foreach ($pumpStatus as $key => $row)
	    {
	        $pumpImages[$key] = "<img width=40px src='/assets/css/pump.".$row.".png'/>";
	    }
        $pumpImages = implode("\n", $pumpImages);
	    return $pumpImages;
	}
	
	private function parseData($data)
	{
	    $data = explode("\n", $data);
	    $pumpStatus = array();
	    foreach ($data as $key => $row)
	    {   
	        if(!empty($row))
	        $pumpStatus[$key] = $row[78].$row[79];
	    }
	    
	    unset ($pumpStatus[0]);
	    unset ($pumpStatus[5]);
	    return $pumpStatus;
	}
	
	private function _actionFilecontent($text,  $client=null)
	{
	    	    echo "A7-";
	    // 	    $this->_sendFileContent($client);
	    // 	    echo "kaka file ";
	    // 	    $data = file_get_contents(substr(__FILE__, 0, strpos(__FILE__, 'server')).'tmp/fileme.md');
	    // 	    echo $data;
	    // 	    $encodedData = $this->_encodeData('file', $data);
	    // 	    foreach($this->_clients as $sendto)
	        // 	    {
	        // 	        $sendto->send($encodedData);
	        // 	    }
	            
// 	            print_r($client->getClientId());
                $i = $this->i;
	            while($i==1)
// 	            while($i==1)
	            {
	                // 	        $data = file_get_contents(substr(__FILE__, 0, strpos(__FILE__, 'server')).'tmp/fileme.md');
	                // 	        $encodedData = $this->_encodeData('file', $data);
	                // 	        sleep(3);
	                usleep(0100000);
	                // 	        $sendto->send($encodedData);
	                $this->_sendFileContent($client);
	                if($this->i==0)
	                    break;
	                // 	        echo time()."\n";
	            }
	            echo "yuju";
	}
	
	
	
	
// 	public function onConnect($client)
//     {
//         echo "1-";
// 		$id = $client->getClientId();
//         $this->_clients[$id] = $client;
// 		$this->_actionState($client);
//     }

    

    
    public function onData($data, $client)
    {
//         echo "2-";
//         $decodedData = $this->_decodeData($data);
//         if($decodedData === false)
//         {
//             // @todo: invalid request trigger error...
//         }
    
//         $actionName = '_action' . ucfirst($decodedData['action']);
//         if(method_exists($this, $actionName))
//         {
//             call_user_func(array($this, $actionName), $decodedData['data'], $client);
//         }
    }
    
    public function onBinaryData($data, $client)
    {
        // currently not in use...
    }
	

	
	
	private function _actionState($text,  $client=null)
	{
	    echo "8-";
        $i=1;
        while($i==1 && (null !== $client->getClientId()))
        {
            usleep(0100000);
            // 	    echo "9-";
	            $data = file_get_contents(substr(__FILE__, 0, strpos(__FILE__, 'server')).'tmp/fileme.md');
	    //         $data = file_get_contents("http://your.server.com:60606/trabajo/ESTADO.DAT");
        	
        // 	    $data = file_get_contents("http://192.168.1.134:60606/trabajo/ESTADO.DAT");
        	    $currentFileContent = array();
        	    $currentFileContent['content'] = $data;
        	    $encodedData = $this->_encodeData('fileContent', $currentFileContent);
        	    $client->send($encodedData);
        }
	}
	
	
}