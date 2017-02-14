<?php
/**
 * AbstractMapper.
 * Base class for descending Model Mappers
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Core
 * @category Classes
 *
 */
namespace Openy\Model;

use Openy\Core\Functions\ArrayFunctions;
use Openy\Model\Hydrator\Strategy\PrimaryKeyStrategy;
use Openy\Model\TableGateway\Feature\FeatureSet;
use Openy\Model\TableGateway\Feature\MetadataFeature;
use Openy\Traits\Properties\OptionsTrait;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\PreparableSqlInterface;
use Zend\Db\Sql\Predicate\PredicateInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Zend\Db\TableGateway\Feature\MasterSlaveFeature;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Stdlib\AbstractOptions;

use Openy\Interfaces\MapperInterface;
use Openy\Interfaces\Properties\OptionsInterface;
use Openy\Traits\MapperTrait;

/**
 * Openy Abstract Base Mapper.
 *
 * Provides common Mapper methods such fetch, fetchAll, insert, delete, deleteAll or update.
 *
 * Moreover provides a pair of functions to help asserting the existence of an Entity inside model:
 * * Locate
 * * Exists
 *
 * @uses  Openy\Interfaces\MapperInterface Interface for Mappers
 *
 */
abstract class AbstractMapper
    implements MapperInterface,
               OptionsInterface
{

    use OptionsTrait;
    //use MapperTrait;

	protected $adapterMaster;
    protected $adapterSlave;
    protected $options;
    protected $currentUser;
    //protected $tableName      ;//= 'tablename';
    //protected $primary        ;//= 'primaryKey';
    protected $secondary = []   ;//= ['secondaryKeyA', 'secondaryKeyB'];
    //protected $tableAliasName ;//= substr('tablename',0,3);
    //protected $entity         ;//= 'Openy\Model\AbstractEntity';
    //protected $collection     ;//= 'Openy\Model\AbstractCollection';
    protected $hydrator       = 'Zend\Stdlib\Hydrator\Reflection';
    protected $dataHydrator   = 'Zend\Stdlib\Hydrator\ObjectProperty';

    protected $featureSet;

    /**
     * Fields whose should'nt be exposed
     * @var Array of String Contains field names whose won't be shown
     */
    protected $hiddenFields   = array();
    //TODO it must be improved depending on action (FETCHALL, FETCH, UPDATE, PATCH)

    /**
     * Fields whose should'nt be updated
     * @var Array of String Contains field names whose won't be updated, then neither extracted
     */
    protected $readOnlyFields = array();
    //TODO it must be improved depending on action (FETCHALL, FETCH, UPDATE, PATCH)


    /**
     * @param AdapterInterface
     * @param AdapterInterface
     * @param AbstractOptions
     * @param [type]
     */
	public function __construct(AdapterInterface $adapterMaster, AdapterInterface $adapterSlave, AbstractOptions $options, $currentUser)
	{
        //TODO: Set class for UserPrefs
        $this->adapterMaster    = $adapterMaster;
        $this->adapterSlave     = $adapterSlave;
        $this->options		    = $options;
        $this->currentUser      = $currentUser;

        $this->setTableName()
             ->setTableAliasName()
             ->setHiddenFields()
             ->setReadOnlyFields()

             ->initMetadata();

	}

        /**
         * Inits Metadata
         * @return Mapper itself
         */
        protected function initMetadata(){
            /*$this->featureSet = new FeatureSet();
            $this->featureSet->addFeature(new MasterSlaveFeature($this->adapterSlave));
            $this->featureSet->addFeature(new MetadataFeature());
            $table = new TableGateway($this->tableName, $this->adapterMaster, $this->featureSet);
            $table->initialize();
            $this->primary = $table->metadata['primaryKey'];*/
            return $this;
        }

        /**
         * Sets Mapper table name or Resets to default.
         * When no tableName has been specified at construction time,
         * the "tableName" option value is assumed as default table value
         * @param string $tableName Name of the mapped table
         * @return Mapper itself
         */
        public function setTableName($tableName=null){
            if (!is_null($tableName))
                $this->tableName = (string)$tableName;
            else if(is_null($this->tableName)){
                if (isset($this->options->tableName))
                    $this->tableName = (string)$this->options->tableName;
            }
            return $this;
        }

        /**
         * Sets Mapper table alias name or Resets to default.
         * Alias name is used in select query joins
         * When no tableName has been specified at construction time,
         * the "tableName" option value is assumed as default table value
         * @param string $tableAliasName Name of the mapped table
         * @return Mapper itself
         */
        public function setTableAliasName($tableAliasName = null)
        {
            if (!is_null($tableAliasName))
                $this->tableAliasName = (string)$tableAliasName;
            else if(is_null($this->tableName)){
                if (isset($this->options->tableAliasName))
                    $this->tableAliasName = (string)$this->options->tableAliasName;
            }
            else if (!isset($this->tableAliasName) || is_null($this->tableAliasName)){
                // If no Table Alias name defined in options,
                // it may be the actual table name initials
                // e.g. "registered_users" will be aliased as "ru"
                $matches= array();
                preg_match_all('/(^([a-z])|(_[a-z][a-z]))/', $this->tableName, $matches);
                $this->tableAliasName = $matches[2][0].implode('',$matches[3]);
            }
            return $this;
        }

        /**
         * Sets Mapper fields not to be exposed (the hidden ones), or resets to default
         * @param mixed $hiddenFields An array containing fields not to be exposed,
         * where an empty array means no fields to hide, and a null value forces a reset to default.
         * @return  Mapper itself
         */
        public function setHiddenFields($hiddenFields=null){
            if (!is_null($hiddenFields) || is_array($hiddenFields)) // hiddenFields can be an empty array
                $this->hiddenFields = (array)$hiddenFields;
            else if(is_null($this->hiddenFields)){
                if (isset($this->options->hiddenFields))
                    $this->hiddenFields = (array)$this->options->hiddenFields;
            }
            return $this;
        }

        /**
         * Sets Mapper fields not to be updated (the read only ones), or resets to default
         * @param mixed $hiddenFields An array containing fields not to be updated,
         * where an empty array means no fields to protect, and a null value forces a reset to default.
         * @return  Mapper itself
         */
        public function setReadOnlyFields($readOnlyFields=null){
            if (!is_null($readOnlyFields) || is_array($readOnlyFields)) // readOnlyFields can be an empty array
                $this->readOnlyFields = (array)$readOnlyFields;
            else if(is_null($this->readOnlyFields)){
                if (isset($this->options->readOnlyFields))
                    $this->readOnlyFields = (array)$this->options->readOnlyFields;
            }
            return $this;
        }


    /**
     *  Returns an instance of preset entity
     *  @param  mixed $excludedFields Fields to be excluded from the Entity
     */
    protected function getEntityInstance(){
        $class = new \ReflectionClass($this->entity);
        $entity = $class->newInstance();
        return $entity;
    }

    /**
     * Returns an instance of preset entity preset hydrator
     * @return Zend\Stdlib\Hydrator\Reflection
     */
    protected function getHydratorInstance(){
        $class = new \ReflectionClass($this->hydrator);
        $hydrator = $class->newInstance();
        return $hydrator;
    }

    /**
     * Returns an instance of preset Hydrator for handling input data
     * @return Zend\Stdlib\Hydrator\ObjectProperty [description]
     */
    protected function getDataHydratorInstance(){
        $class = new \ReflectionClass($this->dataHydrator);
        $dataHydrator = $class->newInstance();
        return $dataHydrator;
    }

    protected function statementExecute(AdapterInterface $adapter, PreparableSqlInterface &$sql){
        $statement = $adapter->createStatement();
        $sql->prepareStatement($adapter, $statement);
        return $statement->execute();
    }


    public function locate($entity){
        $instance = $this->fetchGetEntityInstance();
        $secondaryKeys = (array)$this->secondary;
        $located = is_object($entity)
                   && ((get_class($entity) == get_class($instance)) ||
                       ($instance instanceof $entity) ||
                       ($entity instanceof $instance)
                       );

        // If we are locating using a compatible entity
        if ($located){
            // Single field search
            if (is_scalar($this->primary)){
                // If primary key has no value, then check for secondary candidates (if any)
                if (FALSE == (bool)($entity->{$this->primary})){
                    if (count($secondaryKeys))
                        return $this->locateWithSecondaryKeys($entity);
                    else // If no secondary keys available, then return an empty instance
                        return $instance;
                }
                // Primary key has a value, then fetch with it
                else
                    return $this->fetch($entity->{$this->primary});
            }
            // Multiple field search
            elseif(is_array($this->primary)){
                $entity_primary_values = array_intersect_key(get_object_vars($entity), array_flip($this->primary));
                // If primary key misses any value in its attributes, then check for secondary candidates
                if (in_array(null,$entity_primary_values)){
                    if (count($secondaryKeys))
                        return $this->locateWithSecondaryKeys($entity);
                    else
                        return $instance;
                }
                // Primary key is full populated with values
                else
                    return $this->fetch($entity_primary_values);
            }
        }
        return $instance;
    }

        protected function locateWithSecondaryKeys($entity){
            $result = $entity;
            $primary = $this->primary;
            $secondary = (array)$this->secondary;
            // This is a protection against infinite loop
            if (is_scalar($primary) && !in_array($primary,$secondary)){
                foreach($secondary as $key){
                    $this->primary = $key;
                    $result = $this->locate($entity);
                    // Check if $entity has been located looking its actual primary key
                    if ($result->{$primary})
                        break;
                }
                $this->primary = $primary;
            }
            elseif (is_array($primary) && !ArrayFunctions::in_array($primary, $secondary)){
                foreach($secondary as $key){
                    $this->primary = $key;
                    $result = $this->locate($entity);
                    // Check if $entity has been located looking all members of its actual primary key
                    $mustBreak = true;
                    foreach($primary as $key){
                        $mustBreak = !is_null($entity->{$key}) && $mustBreak;
                    }
                    if ($mustBreak) break;
                }
                $this->primary = $primary;
            }
            return $result;
        }

    public function exists(&$entity,$fetch_entity_if_exists = FALSE){
        $located = $this->locate($entity);
        if (is_scalar($this->primary))
            $result = (bool)($located->{$this->primary});
        elseif(is_array($this->primary)){
            $result = true;
            foreach($this->primary as $prop){
                $result = (bool)($located->{$prop});
                if (!$result) break;
            }
        }

        if ($result && $fetch_entity_if_exists)
            $entity = $located;
        return $result;
    }


	/**
	 * @param  Array $filters Filter criteria to be applied on the result collection to be fetched
	 * @return Zend\Paginator\Paginator;
	 */
    public function fetchAll($filters)
    {

    	// Fetch from Database
    	$query = $this->fetchAllBuildQuery($filters);
		$driverResult = $this->fetchAllStatementExecute($query);
		$resultset = $this->fetchAllPopulateResultset($driverResult);

    	// Collect and paginate results
    	$collection = $this->fetchAllPaginateCollection($query,$resultset);
    	return $collection;
    }

        /**
         *
         * Creates a Query to be executed on DB to fetch expected results
         * @param  Array $filters Filter criteria to be applied on the query
         * @return Zend\Db\Sql\Select
         */
        protected function fetchAllBuildQuery($filters){
    		$query = new Select(array($this->tableAliasName => $this->tableName) );
    		$this->fetchAllBuildQuerySetFilters($filters,$query);
    		return $query;
        }

        	/**
        	 * Applies where conditions to given query based on received filtering criteria
        	 * @param  Array $filters filter criteria
        	 * @return [type]
        	 */
        	protected function fetchAllBuildQuerySetFilters($filters,&$query){
        		// TODO: MAKE FILTERING BE SET ON QUERY BY INJECTED MODULE OPTIONS
        		// if(isset($filters['iduser']))
                // $query->where(array('iduser'=>$filters['iduser']));

                if (is_array($filters)){
                    if (!ArrayFunctions::is_assoc($filters)){
                        $query->where->in($this->primary,$filters);
                    }
                    else{
                        // TODO prepend tableAliasName to $filters' array keys having field names with no alias
                        $query->where($filters);
                    }
                }
                elseif ($filters instanceof PredicateInterface){
                    $query->where($filters);
                }
            	return;
        	}


        /**
         *
         * Executes on Db the given $query and retrieves the result
         * @param  Zend\Db\Sql\Select $query The query to be executed against the Slave Adapter;
         * @return Zend\Db\Adapter\Driver\ResultInterface $driverResult;
         *
         */
        protected function fetchAllStatementExecute(&$query){
            $driverResult = $this->statementExecute($this->adapterSlave,$query);
            return $driverResult;
        }

        /**
         *
         * Populates a resultset with a previous executed query result
         * @param  Zend\Db\Adapter\Driver\ResultInterface $driverResult
         * @return Zend\Db\ResultSet\HydratingResultSet An hydrated resultset for Mapper's Entity with Mapper's Hydrator
         *
         */
    	protected function fetchAllPopulateResultset($driverResult){
    		$entity = $this->fetchAllGetEntityInstance();
        	$hydrator = $this->fetchAllGetHydratorInstance();

            $resultset = new HydratingResultSet;
            $resultset->setHydrator($hydrator);
            $resultset->setObjectPrototype($entity);
            $resultset->initialize($driverResult);

           	return $resultset;
        }

            /**
             *	Returns an instance of collection entity
             */
            protected function fetchAllGetEntityInstance(){
                // Hidden fields won't be exposed in the entity
        		return $this->getEntityInstance($exclude = $this->hiddenFields);
            }

            /**
             * Returns an instance of collection entity hydrator
             * @return Zend\Stdlib\Hydrator\Reflection
             */
            protected function fetchAllGetHydratorInstance(){
            	return $this->getHydratorInstance();
            }


        /**
         *
         * Returns a paginator feed with a collection populated with fetched resultset
         * @param  Zend\Db\Sql\Select $select
         * @param  Zend\Db\ResultSet\ResultSetInterface $resultset
         * @return Zend\Paginator\Paginator
         *
         */
        protected function fetchAllPaginateCollection(Select $select, HydratingResultSet $resultset){
        	// ADVISE: Override this function your descendant class with following code
        	// if you prefer not paginating the returned collection
        	// $collection = $this->collection;

        	// TODO: Paginate when set by injected options

            $paginatorAdapter = new DbSelect(
                $select,
                $this->adapterSlave,
                $resultset
            );

            $class = new \ReflectionClass($this->collection);
            $collection = $class->newInstance($paginatorAdapter);

            return $collection;
        }

    /**
     * Tries to build fetch where conditions from data object depending on Mapper primary key type.
     * When Primary Key is a single attribute, then performs a fetch using the value for this attribute in $data object
     * elsewhere when primary key is compound by multiple attributes, performs a fetch setting $data attributes
     * matching primary key columns in a where clause and leaving "fetch" $id argument as null
     * @param  StdClass $data Object or array from where to extract fetch where condition values
     * @return StdClass       Entity populated with the fetch result
     */
    public function fetchByData($data){
        //TODO: Must match $primary property using Hydrator Naming Strategy
        $dataHydrator = $this->getDataHydratorInstance();
        $data = is_object($data) ? $data : (object)$data;
        $data = $dataHydrator->extract($data);
        $where = array_intersect_key($data,(array)$this->secondary);
        $primaryAsWhere = is_array($this->primary) ? array_intersect_key($data, array_flip($this->primary)) : [];

        if (is_scalar($this->primary) && array_key_exists($this->primary, $data) && !is_null($data[$this->primary]))
            $entity = $this->fetch($data[$this->primary]);
        elseif(is_array($this->primary) && count($primaryAsWhere))
            $entity = $this->fetch(null,$primaryAsWhere);
        elseif (count($where))
            //WARNING: $where can contain subarrays, what will break fetch where condition
            $entity = $this->fetch(null,$where);
        else
            $entity = $this->getEntityInstance();

        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function fetch($id, $where = []){
        if (is_array($id) || is_object($id))
            return $this->fetchByData($id);
        $query = $this->fetchBuildQuery($id,$where);
        $driverResult = $this->fetchStatementExecute($query);
        $result = $driverResult
                ? $this->fetchPopulateResult($driverResult)
                : $this->fetchGetEntityInstance();
        return $result;
    }
        /**
         *
         * @ignore
         */
        protected function fetchBuildQuery($id,$where = []){
            $query = new Select(array($this->tableAliasName => $this->tableName) );
            $query = $this->fetchBuildQuerySetWhere($id,$query,$where);
            return $query;
        }
            /**
             * @ignore
             */
            protected function fetchBuildQuerySetWhere($id,&$query,$where=[]){
                if (!is_null($id))
                    $query->where(array($this->tableAliasName.'.'.$this->primary=>$id));
                //WARNING: $where can contain subarrays, what will break fetch where condition
                $query->where($where);
                return $query;
            }
        /**
         * @ignore
         */
        protected function fetchStatementExecute(&$query){
            $driverResult = null;
            if (count($query->getRawState('where')))
                $driverResult = $this->statementExecute($this->adapterSlave,$query);
            if ($driverResult)
                return $driverResult->current();
            else
                return null; //TODO: What to return here?
        }
        /**
         * @ignore
         */
        protected function fetchPopulateResult($result){
            $entity = $this->fetchGetEntityInstance();
            $hydrator = $this->fetchGetHydratorInstance();
            $entity = $hydrator->hydrate($result,$entity);
            return $entity;
        }

        /**
         *  Returns an instance of single entity
         */
        protected function fetchGetEntityInstance(){
            return $this->getEntityInstance();
        }

        /**
         * Returns an instance of single entity hydrator
         * @return Zend\Stdlib\Hydrator\Reflection
         */
        protected function fetchGetHydratorInstance(){
            return $this->getHydratorInstance();
        }

    public function insert($data){

        if (isset($this->options->replace_when_insert) && $this->options->replace_when_insert){
            $entity = $this->fetchByData($data);

            $data->{$this->primary} = isset($data->{$this->primary}) ? $data->{$this->primary} : null;

            //TODO: DISCUSS THIS BEHAVIOUR
            if ($data->{$this->primary}):
                if ($entity->{$this->primary} == $data->{$this->primary})
                    return $this->replace($data->{$this->primary},$data);
            endif;
        }
        $insert = $this->insertBuildSQL($data);
        $driverResult = $this->insertStatementExecute($insert);
        $result = ($driverResult->getGeneratedValue() || $driverResult->getAffectedRows());

        //TODO: Test following code since return
        //TODO: $this->primary has to be replaced with resulting name from hydrator Naming Strategy
        if ($result){
            $entity = $data;
            $last = $this->adapterMaster->getDriver()->getLastGeneratedValue();
            $entity->{$this->primary}= $last ? : $data->{$this->primary};
        }

        return $entity;
    }

        /**
         *  Returns an instance of entity for being inserted
         *  Entities fields
         */
        protected function insertGetEntityInstance(){
            $entity = $this->getEntityInstance();
            return $entity;
        }

        /**
         * Returns an instance of entity (to be inserted) hydrator
         * @return Zend\Stdlib\Hydrator\Reflection
         */
        protected function insertGetHydratorInstance(){
            $hydrator = $this->getHydratorInstance();
            return $hydrator;
        }


        protected function insertBuildSQL(&$data){
            $insert = new Insert($this->tableName);
            $insert = $this->insertBuildSQLSetValues($data,$insert);
            $insert = $this->insertBuildSQLSetWhere($data,$insert);
            return $insert;
        }

            protected function insertBuildSQLSetValues(&$data, &$insert){

                $dataHydrator = $this->getDataHydratorInstance();
                $hydrator = $this->insertGetHydratorInstance($data);
                $entity = $this->insertGetEntityInstance($data);

                $data = $dataHydrator->extract($data);
                $data = $hydrator->hydrate($data,$entity);
                $values = $hydrator->extract($data);

                if (!empty($values)){
                    $insert->values($values);
                    $data = $hydrator->hydrate($values,$data);
                }

                return $insert;
            }

            protected function insertBuildSQLSetWhere($data, &$insert){
                //TODO : Discuss place an option check.
                //       That option must tell if insert has to be in the form
                //       $insert->select($select)
                //       Where $select has a having(count(*) = 0)
                return $insert;
            }

        protected function insertStatementExecute(&$insert){
            $driverResult = $this->statementExecute($this->adapterMaster,$insert);
            return $driverResult;
        }

    public function replace($id,$data){

    }

    /**
     * Updates database matching row with data received from a patch
     * @param  mixed $id   Value for primary key field
     * @param  array $data [description]
     * @return [type]       [description]
     */
    public function update($id, $data){
        $update = $this->updateBuildSQL($id,$data);
        if (count($update->getRawState('set'))){
            $driverResult = $this->updateStatementExecute($update);
            $result = $driverResult->getGeneratedValue();
        }
        if (is_array($id) && !ArrayFunctions::is_assoc($id))
            return $this->fetchAll($update->where);
        else
            return $this->fetch($id);
    }

        protected function updateBuildSQL($id,$data){
            $update = new Update($this->tableName);
            $this->updateBuildSQLSetSet($data,$update);
            $this->updateBuildSQLSetWhere($id,$update,$data);
            return $update;
        }

            protected function updateBuildSQLSetSet($data,&$update){
                $values = $this->updateBuildSQLSetValues($data);
                if (!empty($values))
                    $update->set($values);
                return $update;
            }

            /**
             * Defines which fields ($values) are gonna be updated
             * @param  array $data the received data
             * @return [type] [description]
             */
            protected function updateBuildSQLSetValues($data){
                $dataHydrator = $this->getDataHydratorInstance($data);
                $data = $dataHydrator->extract($data);

                $hydrator = $this->updateGetHydratorInstance($data);
                $entity = $this->updateGetEntityInstance();

                $entity = $hydrator->hydrate($data,$entity);
                $values = $hydrator->extract($entity);

                //TODO: IMPROVE THIS CAUSE IS A BIT RISKY
                // intersection must be performed agains hydrator namimg strategy, not $data keys
                return array_intersect_key($values,$data);
            }

            protected function updateBuildSQLSetWhere($id,&$update,$data=null){
                if (is_array($id)):
                    $update->where->in($this->primary,$id);
                else:
                    $update->where(array($this->primary => $id));
                endif;
                return $update;
            }

        /**
         *  Returns an instance of entity for being updated
         *  Entities fields
         */
        protected function updateGetEntityInstance(){
            $entity = $this->getEntityInstance();
            return $entity;
        }

        /**
         * Returns an instance of entity (to be updated) hydrator
         * @return Zend\Stdlib\Hydrator\Reflection
         */
        protected function updateGetHydratorInstance(){
            $hydrator = $this->getHydratorInstance();
            return $hydrator;
        }

        protected function updateStatementExecute(Update $update){
            $driverResult = $this->statementExecute($this->adapterMaster,$update);
            return $driverResult;
        }

    /**
     * Deletes a row from database
     * @param  mixed $id Identifier for mapped table
     * @return Entity|FALSE Entity before deletion or FALSE if error
     */
    public function delete($id){
        $result = $this->fetch($id);
        $delete = $this->deleteBuildQuery($result->{$this->primary});
        if (count($delete->getRawState('where'))){
            $driverResult = $this->deleteStatementExecute($delete);
            $return = ($driverResult->getGeneratedValue() || $driverResult->getAffectedRows());
            return (bool)$result;
        }
        return false;
    }

        protected function deleteBuildQuery($id){
            $delete = new Delete();
            $delete->from($this->tableName);
            $delete = $this->deleteBuildQuerySetWhere($id,$delete);
            return $delete;
        }

            protected function deleteBuildQuerySetWhere($id,&$delete){
                if (!is_null($id))
                    $delete->where(array($this->primary => $id));
                return $delete;
            }

        protected function deleteStatementExecute(&$delete){
            $driverResult = $this->statementExecute($this->adapterMaster,$delete);
            return $driverResult;
        }


    /**
     * Deletes all rows from database
     * @param  mixed $id Identifier for mapped table
     * @return Entity|FALSE Entity before deletion or FALSE if error
     */
    public function deleteAll($filter){
        $delete = $this->deleteAllBuildQuery($filter);
        $driverResult = $this->deleteAllStatementExecute($delete);
        $return = ($driverResult->getGeneratedValue() || $driverResult->getAffectedRows());
        return (bool)$return;
    }

        /**
         * [deleteAllBuildQuery description]
         * @param  [type] $filter [description]
         * @return Zend\Db\Sql\Delete         [description]
         */
        protected function deleteAllBuildQuery($filter){
            $delete = new Delete();
            $delete->from($this->tableName);
            $delete = $this->deleteAllBuildQuerySetWhere($filter,$delete);
            return $delete;
        }

            /**
             * Defines and assigns a where condition for the $delete based on given $filter
             * @param  Array $filter  [description]
             * @param  Zend\Db\Sql\Delete &$delete [description]
             * @return Zend\Db\Sql\Delete          [description]
             */
            protected function deleteAllBuildQuerySetWhere($filter,&$delete){
                return $delete;
            }

        /**
         * Performs the delete execution on Master DB
         * @param  Zend\Db\Sql\Delete $delete [description]
         * @return [type]         [description]
         */
        protected function deleteAllStatementExecute($delete){
             $driverResult = $this->statementExecute($this->adapterMaster,$delete);
             return $driverResult;
        }
}
