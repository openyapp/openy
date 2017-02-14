<?php
namespace Admin\Model;

use Zend\Db\Adapter\Adapter;
use Admin\Model\StationEntity;
use Zend\Stdlib\Hydrator\ObjectProperty;

use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\Stdlib\Hydrator\Reflection;


use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;

class StationMapper
{
    protected $tableName = 'off_station';
    protected $keyColumn = 'idoffstation';
    protected $dbAdapter;
    protected $sql;

    public function __construct(Adapter $dbAdapter)
    {
        mb_internal_encoding('UTF-8');
        $this->dbAdapter = $dbAdapter;
        $this->sql = new Sql($dbAdapter);
        $this->sql->setTable($this->tableName);
    }

    public function fetchAll()
    {
        $select = $this->sql->select();
//         $select->order(array('completed ASC', 'created ASC'));

        $statement = $this->sql->prepareStatementForSqlObject($select);
        
//         echo $select->getSqlString($this->dbAdapter->getPlatform());
        
        
        $results = $statement->execute();

        $entityPrototype = new StationEntity();
        $hydrator = new Reflection();
        $resultset = new HydratingResultSet($hydrator, $entityPrototype);
        $resultset->initialize($results);
        return $resultset;
    }
    
    public function getIdoffstation($data)
    {
        $select = $this->sql->select();
        $select->where(array('ilat' => $data['lat'],
                             'ilng' => $data['lng']
        ));
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute()->current();
        return $result;
    }
    public function save($task)
    {
        $entityPrototype = new StationEntity();
        $hydrator = new Reflection();
        $data = $hydrator->hydrate((array) $task, $entityPrototype);
        $data = $hydrator->extract($entityPrototype);
        $data['ilat']=$data['lat'];
        $data['ilng']=$data['lng'];
        $result = $this->getIdoffstation($data);
         
        if (!$result) {     // Insert GasStation
            
            $data['created'] = date("Y-m-d");
            $data['idstationtype'] = 1;         // Only for Gasolineras. Postes Maritimos TODO
            $data['idopeny'] = '0';         
            
            $action = $this->sql->insert();
            $action->values($data);
//             echo $action->getSqlString();
//             die ("kaka");
            $statement = $this->sql->prepareStatementForSqlObject($action);
            $result = $statement->execute();
            return $result->getGeneratedValue();
        }
        else{               // Update GasStation Official data 
            
            if($this->isDifferent($result, $data))
            {
                $data['updated'] = date("Y-m-d");
                $data['idoffstation'] = $result['idoffstation'];
                $data['idstationtype'] = 1;
                $action = $this->sql->update();
                unset($data['address']);
                $action->set($data);
                $action->where(array('idoffstation' => $result['idoffstation']));
                //echo $action->getSqlString();
                $statement = $this->sql->prepareStatementForSqlObject($action);
                $result = $statement->execute();
                return $result->getGeneratedValue();
            }
            
        }
        return $result['idoffstation'];
    }
    
    public function saveLocality($data)
    {
        //print_r($data);
        $data = (array)$data;
        $localidad = ucfirst(mb_strtolower(trim($data['Localidad'])));
        $provincia = $data['Provincia'];
        $municipio = $data['Municipio'];
        //print_r($data);
        $sql = "SELECT idlocality
                FROM locality
                WHERE locality LIKE \"".$localidad."\"";
        //echo $sql."\n";
        $statement = $this->dbAdapter->createStatement($sql);
        $statement->prepare($sql);
        $result = $statement->execute();
        $val = $result->current();
        
        if($result->count()==0)
        {
            $municipality = $this->getMunicipality($municipio, $provincia);
            $sql = "INSERT INTO locality (locality, idmunicipality) values (:locality, :idmunicipality)";
            //echo $sql."\n";
            $data2 = array('locality'=>$localidad, 
                          'idmunicipality'=>$municipality['idmunicipality']
            );
            //print_r($data2);
            $statement = $this->dbAdapter->createStatement($sql,$data2);
            $result2 = $statement->execute();
            return $result2->getGeneratedValue();
        }
    
        else {    
            return $val['idlocality'];
        }
    }
    
    private function getProvince($province)
    {
        $result = preg_match("/[^(\/]/",$province);
        if($result)
        {
            $keywords = preg_split("/[(\/]+/", $province);
            //print_r($keywords);
            $province = trim($keywords[0]);
        }
        $sql = "SELECT idcountry, idprovince
                FROM province
                WHERE province LIKE \"%".$province."%\" 
                OR FIND_IN_SET(\"".$province."\", alternative_name);";
        //echo $sql;
        $statement = $this->dbAdapter->createStatement($sql);
        $statement->prepare($sql);
        $result = $statement->execute();
        $val = $result->current();
        return $val;
    }
    
    private function insertMunicipality($municipality, $province)
    {
        $provinceData = $this->getProvince($province);
        $sql = "INSERT INTO municipality (idprovince, code_municipality, municipality) values (:idprovince, 0, :municipality)";
        $data2 = array('idprovince'=>$provinceData['idprovince'],
            'municipality'=>$municipality
        );
        //echo $sql;
        //print_r($data2);
        $statement = $this->dbAdapter->createStatement($sql,$data2);
        $result2 = $statement->execute();
        return $result2->getGeneratedValue();
    }
    
    private function getMunicipality($municipality, $province)
    {
        
        $municipalityor = $municipality;
        $result = preg_match("/[^(\/]/",$municipality);
        if($result)
        {            
            $keywords = preg_split("/[(\/]+/", $municipality);
            //print_r($keywords);
            $municipality = trim($keywords[0]);           
        }
        $sql = "SELECT idmunicipality, idprovince
                FROM municipality
                WHERE municipality LIKE \"%".$municipality."%\"";
        //echo $sql;
        $statement = $this->dbAdapter->createStatement($sql);
        $statement->prepare($sql);
        $result = $statement->execute();
        
        if($result->count()==0)
        {
            $idMunicipality = $this->insertMunicipality($municipalityor, $province);
            
            $sql = "SELECT idmunicipality, idprovince
                    FROM municipality
                    WHERE idmunicipality =".$idMunicipality;
            //echo $sql;
            $statement = $this->dbAdapter->createStatement($sql);
            $statement->prepare($sql);
            $result = $statement->execute();
        }
        $val = $result->current();
        return $val;
    }
    
    public function saveAddress($data)
    {
//         print_r($data);
        $locality = $this->saveLocality($data);
        
        $address = array('address'=>$data['Dirección'],
                         'idlocality'=>$locality,
        );
//         print_r($address);
        $search  = array(',');
        $replace = array('.');
        
        $data['Latitud'] = str_replace($search, $replace, $data['Latitud']);
        $data['Longitud (WGS84)'] = str_replace($search, $replace, $data['Longitud (WGS84)']);
        
        
        //Haversine formula
        // replace 3959 for 6371 to work in kilometers
        if(!isset($data['Latitud']) || !isset($data['Longitud (WGS84)']))
            return false;
        
        $sql = "SELECT idoffstation, ( 3959 * acos( cos( radians(".$data['Latitud'].") ) * cos( radians( lat ) ) * cos( radians( lng ) -
    	radians(".$data['Longitud (WGS84)'].") ) + sin( radians(".$data['Latitud'].") ) * sin( radians( lat ) ) ) ) AS distance
    	FROM off_station
    	HAVING distance < 0.001 OR distance IS NULL
    	ORDER BY distance";
         
//         echo $sql;
//         die;
        $statement = $this->dbAdapter->query($sql);
        $result = $statement->execute();
        $result = $statement->execute()->current();
//         print_r($result);
        if ($result) 
        {
            
            $action = $this->sql->update();
            $action->set($address);
            $action->where(array('idoffstation' => $result['idoffstation']));
            
            /* Testing point
            if($data['Dirección']=='POLIGONO SANSINENEA ERREKA, 14')
            {
                print_r($data);
                echo @$action->getSqlString();
            }
            */
            
            $statement = $this->sql->prepareStatementForSqlObject($action);
            $result = $statement->execute();
        }
        return false;
    }
    
    protected function isDifferent($result, $data)
    {
//         print_r($result);
//         print_r($data);
        
        $result_array = array_intersect_assoc($result, $data);
//         print_r($result_array);
        
        if(array_key_exists('name', $result_array) && 
            array_key_exists('ilat', $result_array) && 
            array_key_exists('ilng', $result_array)
             )
            return false;
        else
            return true;
        
    }
    
    public function saveWithCoods($task)
    {
        $entityPrototype = new StationEntity();
    	$hydrator = new Reflection();
    	$data = $hydrator->hydrate((array) $task, $entityPrototype);
    	$data = $hydrator->extract($entityPrototype);

    	$sql = "SELECT idoffstation, ( 3959 * acos( cos( radians(".$data['lat'].") ) * cos( radians( lat ) ) * cos( radians( lng ) -
    	radians(".$data['lng'].") ) + sin( radians(".$data['lat'].") ) * sin( radians( lat ) ) ) ) AS distance
    	FROM off_station
    	HAVING distance < 0.001 OR distance IS NULL
    	ORDER BY distance";
    	
    	$statement = $this->dbAdapter->query($sql);
    	$result = $statement->execute();
    	$result = $statement->execute()->current();
    	
    	if (!$result) {
    	    $action = $this->sql->insert();
    		$action->values($data);
        	$statement = $this->sql->prepareStatementForSqlObject($action);
        	$result = $statement->execute();
        	return $result->getGeneratedValue();
    	}
    	return $result['idoffstation'];    
    }
    
    public function get($id)
    {
    	$select = $this->sql->select();
    	$select->where(array('id' => $id));
    
    	$statement = $this->sql->prepareStatementForSqlObject($select);
    	$result = $statement->execute()->current();
    	if (!$result) {
    		return null;
    	}
    
    	$hydrator = new ClassMethods();
    	$task = new StationEntity();
    	$hydrator->hydrate($result, $task);
    
    	return $task;
    }
    
    public function delete($id)
    {
    	$delete = $this->sql->delete();
    	$delete->where(array('id' => $id));
    
    	$statement = $this->sql->prepareStatementForSqlObject($delete);
    	return $statement->execute();
    }
}