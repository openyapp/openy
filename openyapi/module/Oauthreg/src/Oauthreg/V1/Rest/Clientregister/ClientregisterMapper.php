<?php 
namespace Oauthreg\V1\Rest\Clientregister;

use DomainException;
use InvalidArgumentException;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Delete;

use Zend\Crypt\Password\Bcrypt;
use Zend\Db\Adapter\AdapterInterface; 
use Zend\Paginator\Adapter\DbSelect; 
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\AbstractOptions;

use Rhumsaa\Uuid\Uuid;  

class ClientregisterMapper 
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;      
    private $tableName      = 'app_register';
    private $tableNameOauth = 'oauth_clients';
    private $entity         = 'Oauthreg\V1\Rest\Clientregister\ClientregisterEntity';
    private $collection     = 'Oauthreg\V1\Rest\Clientregister\ClientregisterCollection';
    private $hydrator       = 'Zend\Stdlib\Hydrator\Reflection';
    
    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options)
    {
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
        $this->options		    = $options;    
    }
    
    public function fetchAll($filter)
    {
        
        $select = new Select($this->tableName);
               
        /**
         * Filters
        */
                     
              
        
//         echo $select->getSqlString();
    
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
    
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();        
        
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
        
        $resultset = new HydratingResultSet;
        $resultset->setHydrator($hydrator);
        $resultset->setObjectPrototype($entity);
        $resultset->initialize($driverResult); 
        
        $paginatorAdapter = new DbSelect(
            $select,
            $this->adapterSlave,
            $resultset
        );
        
        $class = new \ReflectionClass($this->collection);
        $collection = $class->newInstance($paginatorAdapter);
        
        return $collection;
    }
    
    public function findByPublicKey($publicKey)
    {
        if (!isset($publicKey)) {
            throw new DomainException('Invalid key provided', 404);            
        }
         
        $select = new Select($this->tableName);
        $select->where(array('publicKey' => $publicKey));
        //     	var_dump($select->getSqlString());
         
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
         
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
        
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();
        
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype($entity);
        $resultSet->setHydrator($hydrator);
        $resultSet->initialize($driverResult);
        
        if (0 === count($resultSet)) {
//             throw new DomainException('Not found', 404);
            return false;
        }
        
        return $resultSet->current();
    }
    
    /**
     * @param string $id
     * @return Entity
     */
    public function fetch($id)
    {
        if (!isset($id)) {
            throw new DomainException('Invalid identifier provided', 404);
        }
         
        $select = new Select($this->tableName);
        $select->where(array('privatekey' => $id));
         
        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();
         
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
        
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();
        
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype($entity);
        $resultSet->setHydrator($hydrator);
        $resultSet->initialize($driverResult);
        
        if (0 === count($resultSet)) {
            throw new DomainException('Not found', 404);
        }
    
        return $resultSet->current();
    }
    
    public function save($data, $id = null)
    {
        $data = (array)$data;

        if (isset($id)) {
            $data['privatekey'] = $id;
        }
         
        if (isset($data['privatekey'])) {
            // update action
            $action = new Update($this->tableName);
            $action->set($data);
            $action->where(array('privatekey' => $id));
            
            $statement = $this->adapterMaster->createStatement();
            $action->prepareStatement($this->adapterMaster, $statement);
            $driverResult = $statement->execute();
            
            $data =  $this->fetch($id);
            
        } else {
            // Generate key/pair
            $keys = $this->getKeys(serialize($data).time());
            $data['publickey'] = $keys['pubKey'];
            $data['privatekey'] = $keys['privKey'];
            if(isset($data['registerid']))
                $data['registerid'] = $data['registerid'];
            
//             print_r($data);
            // insert action
            $action = new Insert($this->tableName);
            $action->values($data);
            
            $statement = $this->adapterMaster->createStatement();
            $action->prepareStatement($this->adapterMaster, $statement);
            $driverResult = $statement->execute();
            
            
//             print_r($data);
//             $data['privatekey']= $this->adapterMaster->getDriver()->getLastGeneratedValue();
            $data =  $this->fetch($data['privatekey']);
            $this->setOauthClient($data);
        }
    
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
         
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();
        $hydrator->hydrate((array) $data, $entity);
        
        return $entity;
    }
    
    private function setOauthClient($vals)
    {
        $data['client_id'] = $vals->privatekey;
        
        $crypto  = new Bcrypt;
        $crypto->setCost($this->options->getPasswordCost());
        $pass = $crypto->create($vals->publickey);
        
        if($this->options->getIsEnableXapikeyHeader())
        {
            $data['client_secret'] = $pass;
        }
        
        $action = new Insert($this->tableNameOauth);
        $action->values($data);
        
        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        
        return $driverResult;
        
    }
    
    function hexToStr($hex){
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2){
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }
    
    function CreatePasswordHash($_password, $_salt)
    {
        $saltAndPwd = $_password . $_salt;
        $hashedPwd = hash('sha512', $saltAndPwd);
    
        $hex_strs = str_split($hashedPwd,2);
    
        foreach($hex_strs as &$hex) {
            $hex = preg_replace('/^0/', '', $hex);
        }
        $hashedPwd = implode('', $hex_strs);
    
        return strtoupper($hashedPwd);
    }
    
    function bin2text($bin_str)
    {
        $text_str = '';
        $chars = explode("\n", chunk_split(str_replace("\n", '', $bin_str), 8));
        $_i = count($chars);
        print_r($chars); 
        echo count($chars);
//         for ($a = 0; $a<count($chars))
//         for($i = 0; $i < $_i; $text_str .= chr(bindec($chars[$i])), $i  );
//         return $text_str;
    }
    
    public function hash2($text)
    {
        $text = mb_convert_encoding($text, 'UTF-16LE');
        print_r($text);
        $encrypted = hash("sha512", $text, true);
        return base64_encode($encrypted);
        
        $str = "r/xKd16PbA4qXCe3a1sSlBc7wqHcQmMoQ7dJk6eRlOAPTihWPRU4YX7CA52rYW7xTuRnkr2aRR+ILDijSs3uKA==";
        echo "password: ".$str;
        echo "\n";
        echo "\n";
        echo $this->hash2('opy_1');
        
    }
    
    private function getKeys($name)
    {
        $crypto  = new Bcrypt;
        $crypto->setCost($this->options->getPasswordCost());
        
        
        
        $uuid = Uuid::uuid5($this->options->getDnUuid(), $name);
        $pass = $crypto->create($uuid);
        
        
//         print_r($uuid);
//         print_r($pass);
        return array('privKey'=>$uuid->__toString(),'pubKey'=>$pass); 
    }
    
    private function getOpenSSLKeys()
    {
        echo "kaka";
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        
        // Create the private and public key
        $res = openssl_pkey_new($config);
        // Extract the private key from $res to $privKey
        openssl_pkey_export($res, $privKey);
        // Extract the public key from $res to $pubKey
        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey["key"];
        
        print_r($privKey);
        print_r($pubKey);
        die;
    }
    
    public function delete($id)
    {
        if (!isset($id)) {
            throw new DomainException('Invalid identifier provided', 404);
        }
         
        $delete = new Delete($this->tableName);
        $delete->where(array('privatekey' => $id));
         
        $statement = $this->adapterMaster->createStatement();
        $delete->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();
        return true;
    }
}
