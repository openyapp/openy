<?php
namespace Admin\Model;

use Zend\Db\Adapter\Adapter;
use Admin\Model\PriceEntity;
use Zend\Stdlib\Hydrator\ObjectProperty;

use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\Stdlib\Hydrator\Reflection;


use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;

class PriceMapper
{
    protected $tableName = 'off_price';
    protected $keyColumn = 'idoffstation';
    protected $dbAdapter;
    protected $sql;
    protected $failsFilename;
    public $countChanges;
    

	

	public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        $this->sql = new Sql($dbAdapter);
        $this->sql->setTable($this->tableName);
    }

    public function fetchAll()
    {
        $select = $this->sql->select();
        $statement = $this->sql->prepareStatementForSqlObject($select);
        
        $results = $statement->execute();

        $entityPrototype = new PriceEntity();
        $hydrator = new Reflection();
        $resultset = new HydratingResultSet($hydrator, $entityPrototype);
        $resultset->initialize($results);
        return $resultset;
    }
    
    public function truncate()
    {
        $statement = $this->dbAdapter->query('TRUNCATE TABLE '.$this->tableName);
        $result = $statement->execute();
        return $result;
        
    }
    
 
    public function cycleFailsFilename()
    {
        $directory = 'data/officialstations/es';        
        $failsFilename = $directory.'/fails-'.date("d-m-y").'.json';        
        $a=1;
        while(file_exists($failsFilename))
        {
            $failsFilename = $directory.'/fails-'.date("d-m-y").'-'.$a.'.json';
            $a++;
        }
        $this->failsFilename = $failsFilename;
        return $this;
    }
    
    public function getVarPrice($data)
    {
        $select = $this->sql->select();
        $select->where(array('idoffstation' => $data['idoffstation'],
                             'idfueltype' => $data['idfueltype'],
                             'iprice' => $data['price']));
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute()->current();
        return $result;        
    }
    
    public function save($task)
    {
        
        $fails = array();
        $entityPrototype = new PriceEntity();
    	$hydrator = new Reflection();
    	$data = $hydrator->hydrate((array) $task, $entityPrototype);
    	$data = $hydrator->extract($entityPrototype);
    	$data['iprice'] = $data['price'];
    	
        	try
        	{
        	    $resultC = $this->getVarPrice($data);
        	    if(!$resultC)       // Insert price
        	    {
        	        $data['created'] = date("Y-m-d");
        	        $this->countChanges++;
        	        $action = $this->sql->insert();
        	        $action->values($data);
        	        $statement = $this->sql->prepareStatementForSqlObject($action);
        	        $result = $statement->execute();        	        
        	    }        	    
        	    
        	}
        	catch (\Exception $e) 
        	{
        	    unset($data['created']);
        	    $fails[]=$data;
        	    $data['updated'] = date("Y-m-d");
        	    $action = $this->sql->update();
        	    $action->set($data);
        	    $action->where(array('idoffstation' => $data['idoffstation'],
        	                         'idfueltype' => $data['idfueltype']
        	               ));
//         	    echo $action->getSqlString();
        	    $statement = $this->sql->prepareStatementForSqlObject($action);
        	    $result = $statement->execute();
        	    file_put_contents($this->failsFilename, serialize($fails)."\n", FILE_APPEND);
        	}
        	catch (\Exception $e) 
        	{
        	    echo "Imposible to insert or update";
        	    print_r($data);
        	}

        	
        	if(isset($result))
       	        return $result->getGeneratedValue();
        	
        	return;
    }
    
    
    
}