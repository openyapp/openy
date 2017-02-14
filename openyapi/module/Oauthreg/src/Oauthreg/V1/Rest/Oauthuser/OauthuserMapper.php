<?php
namespace Oauthreg\V1\Rest\Oauthuser;

use DomainException;
use InvalidArgumentException;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Delete;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\AbstractOptions;

// use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Stdlib\Hydrator\Reflection;
// use Zend\Stdlib\Hydrator\ClassMethods;

use Zend\Crypt\Password\Bcrypt;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class OauthuserMapper
{
    protected $adapterMaster;
    protected $adapterSlave;
    protected $options;
    protected $request;

    private $tableName  = 'oauth_users';
    private $entity     = 'Oauthreg\V1\Rest\Oauthuser\OauthuserEntity';
    private $collection = 'Oauthreg\V1\Rest\Oauthuser\OauthuserCollection';
    private $idColumn   = 'username';

    public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options, $request)
    {
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
        $this->options		    = $options;
        $this->request          = $request;
    }

    public function fetchAll($filter)
    {
        $select = new Select($this->tableName);

        /**
         * Filters
         */
        isset($filter['username'])?$select->where(array('username' => $filter['username'])):null;

        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();

        $resultset = new HydratingResultSet;
        $resultset->setObjectPrototype($entity);

        $paginatorAdapter = new DbSelect(
            $select,
            $this->adapterSlave,
            $resultset
        );

        $class = new \ReflectionClass($this->collection);
        $collection = $class->newInstance($paginatorAdapter);

//         $collection = new OauthUserCollection($paginatorAdapter);
        return $collection;
    }

    private function isBearerUser($id)
    {
        $request = $this->request;
        $headers = $request->getHeaders();

        $authentication = $headers->get('Authorization')
                                  ->getFieldValue();

        $publicKey = $this->extractPublicKey($authentication);

        if($publicKey)
            $user = $this->getUserByBearer($publicKey);


        if($user)
            $user = $this->getIdUser($user['user_id']);

        if ($id !== $user['iduser'])
        {
            //throw new DomainException('Invalid identifier provided', 404);
            return false;
        }
        else
            return true;
    }
    /**
     * @param string $id
     * @return Entity
     */
    public function fetch($id)
    {
        if($id==='userinfo')
        {
            $request = $this->request;
            $headers = $request->getHeaders();
        
            $authentication = $headers->get('Authorization')
                                      ->getFieldValue();
        
            $publicKey = $this->extractPublicKey($authentication);
            $id = $this->getUserByBearer($publicKey);  
            $user = $this->getIdUser($id['user_id']);
            $id =$user['iduser'];
        }
        
        if(!$this->isBearerUser($id))
        {
            return new ApiProblemResponse(
                new ApiProblem(
                    400 ,
                    'Invalid identifier provided',
                    'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                    'Bad Request'
                )
            );
        }
        
       
        

    	$select = new Select($this->tableName);
    	$select->where(array('iduser' => $id));
//     	var_dump($select->getSqlString());

    	$statement = $this->adapterSlave->createStatement();
    	$select->prepareStatement($this->adapterSlave, $statement);
    	$driverResult = $statement->execute();

    	$resultSet = new HydratingResultSet;
    	$resultSet->setObjectPrototype(new $this->entity());
    	$resultSet->initialize($driverResult);

    	if (0 === count($resultSet)) {
    		//throw new DomainException('Not found', 404);
    		return new ApiProblemResponse(
    		    new ApiProblem(
    		        404 ,
    		        'Invalid identifier provided',
    		        'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404' ,
    		        'Not found'
    		    )
    		);
    	}

    	$entity = new $this->entity();
    	$entity->populate($resultSet->current());
    	return $entity;
    }

    public function getClientByBearer($bearer)
    {
        $select = new Select('oauth_access_tokens');
        $select->where(array('access_token' => $bearer));

        //var_dump($select->getSqlString());

        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();

        $resultSet = new HydratingResultSet;
        $resultSet->initialize($driverResult);

        if(count($resultSet)!=1)
            return false;

        $result = $resultSet->current();
        return $result['client_id'];
    }

    public function getUserByBearer($bearer)
    {
        $select = new Select('oauth_access_tokens');
        $select->where(array('access_token' => $bearer));

        //var_dump($select->getSqlString());

        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();

        $resultSet = new HydratingResultSet;
        $resultSet->initialize($driverResult);

        if(count($resultSet)!=1)
            return false;

        return $resultSet->current();
    }

    protected function extractPublicKey($authentication)
    {
        $authentication = explode('Bearer ',$authentication);

        if(isset($authentication[1]))
            return $authentication[1];
        else
            return false;
    }

    public function getIdUser($username)
    {
        $select = new Select($this->tableName);
        $select->where(array('username' => $username));

        //var_dump($select->getSqlString());

        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();

        $resultSet = new HydratingResultSet;
        $resultSet->initialize($driverResult);

        return $resultSet->current();
    }

    public function patch($data, $id = null)
    {
        $data = (array)$data;


        if(!$this->isBearerUser($id))
            {
                return new ApiProblemResponse(
                    new ApiProblem(
                        400 ,
                        'Invalid identifier provided',
                        'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                        'Bad Request'
                    )
                );
            }

            // update action
            $action = new Update($this->tableName);
            if(isset($data['newpassword']) and $data['password'] )
            {
                $user = $this->getUserName($id);
                if($user->verifyPassword($data['password']))
                {
                    $data['password'] = $this->cryptPassword($data['newpassword']);

                    unset($data['newpassword']);
                }
                else
                {
                    return new ApiProblemResponse(
                        new ApiProblem(
                            400 ,
                            'Password not match',
                            'http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400' ,
                            'Bad Request'
                        )
                    );
                }

            }
            else
            {
                unset($data['password']);
                unset($data['newpassword']);
            }


            $action->set($data);
            $action->where(array('iduser' => $id));
            // var_dump($action->getSqlString());

        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();

        $userData = $this->getUserName($id);
        return $userData;
    }

    public function save($data, $id = null)
    {

        $data = (array)$data;

        if (!empty($id)) {
            $data['iduser'] = $id;
        }

        $entity = new $this->entity();
//                 \Zend\Debug\Debug::dump($entity, "entity before : ");
        $hydrator = new Reflection();
        $hydrator->hydrate($data, $entity);
        $data = $hydrator->extract($entity);
//         \Zend\Debug\Debug::dump($data, "data after : ");

        $userData = $this->getUserName($id);
        $data['first_name'] = $userData->first_name;
        $data['last_name'] = $userData->last_name;

        if (isset($data['username'])) {
            // update action
            $action = new Update($this->tableName);
            if(isset($data['password']))
                $data['password'] = $this->cryptPassword($data['password']);
            $action->set($data);
            $action->where(array($this->idColumn => $id));
        } else {
            // insert action
            $action = new Insert($this->tableName);
            $data['password'] = $this->cryptPassword($data['password']);
            $action->values($data);
        }
        //      var_dump($action->getSqlString());

        $statement = $this->adapterMaster->createStatement();
        $action->prepareStatement($this->adapterMaster, $statement);
        $driverResult = $statement->execute();

        $hydrator->hydrate($data, $entity);
        return $entity;
    }

    public function delete($id)
    {
    	if ($id == 0) {
    	    throw new DomainException('Invalid identifier provided', 404);
    	}

    	$delete = new Delete($this->tableName);
    	$delete->where(array('iduser' => $id));

    	$statement = $this->adapterMaster->createStatement();
    	$delete->prepareStatement($this->adapterMaster, $statement);
    	$driverResult = $statement->execute();
    	return true;
    }

    private function cryptPassword($password)
    {
        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->options->getPasswordCost());
        $password = $bcrypt->create($password);

        return $password;
    }

    private function getUserName($id)
    {
        if (empty($id)) {
            return false;
        }

        $select = new Select('oauth_users');
        $select->where(array('iduser' => $id));
        //var_dump($select->getSqlString());

        $statement = $this->adapterSlave->createStatement();
        $select->prepareStatement($this->adapterSlave, $statement);
        $driverResult = $statement->execute();

        $entity = new $this->entity();
        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype($entity);
        $resultSet->initialize($driverResult);

        if (0 === count($resultSet)) {
            return false;
        }


        $entity->populate($resultSet->current());
        return $entity;
    }



}
