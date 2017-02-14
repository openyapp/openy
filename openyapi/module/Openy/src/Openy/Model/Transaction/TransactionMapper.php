<?php

namespace Openy\Model\Transaction;

use Openy\Model\AbstractMapper;
use Openy\Model\Hydrator\Strategy\UuidStrategy;
use Openy\Model\Hydrator\Strategy\CurrentTimestampStrategy;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;
use Openy\Model\Transaction\TransactionEntity;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;

use Openy\Model\Classes\CreditCardDataEntity;
use ZF\ApiProblem\ApiProblem;
//TODO REMOVE Update use
use Zend\Db\Sql\Update;

class TransactionMapper extends AbstractMapper
{

 protected $tableName      = 'opy_tpv_transaction';
 protected $primary        = 'transactionid';
    //protected $tableAliasName ;//= substr('tablename',0,3);
 //protected $joinTableNames = ['tokens'=>['vcd'=>'opy_validated_card']];
 protected $entity         = 'Openy\Model\Transaction\TransactionEntity';
 protected $collection     = 'Zend\Paginator\Paginator';
 protected $hydrator       = 'Openy\Model\Hydrator\TransactionHydrator';

 protected $mappingStrategy = ['lastresponsecode' => 'lastresponse',
                               'transactiontype' => 'transactionType',
                              ];


    protected function getEntityInstance($excludedFields=null){
        return new TransactionEntity(null, null, null, null, null, new CreditCardDataEntity(), null);
    }

    public function locate($entity){
        $instance = $this->fetchGetEntityInstance();
        $located = is_object($entity) && (get_class($entity) == get_class($instance));
        $located = $located && (bool)($entity->{$this->primary});

        if ($located){
            $query = $this->fetchBuildQuery($entity->{$this->primary});
            $driverResult = $this->statementExecute($this->adapterSlave,$query);
            if ($driverResult){
                if (($entity instanceof \Openy\Model\Transaction\TransactionEntity)
                   && (isset($entity->transactionType)))
                {
                        foreach($driverResult as $current){
                            if ((string)$current['transactiontype'] === (string)$entity->transactionType){
                                return $this->fetchPopulateResult($current,$entity);
                            }
                        }
                }
                elseif ($driverResult->current()){
                    return $this->fetchPopulateResult($driverResult->current(),$entity);
                }
            }
        }

        return $instance;
    }

    protected function fetchPopulateResult($result,$entity=null){
        $return = parent::fetchPopulateResult($result);
        if (!is_null($entity) && ($entity->{$this->primary} == $return->{$this->primary})){
            $return->secret = isset($return->secret) ? $return->secret : $entity->secret;
            $return->token = isset($return->token) ? $return->token : $entity->token;
        }
        return $return;
    }

    /**
     * {@inheritDoc}
     *
     */
    protected function getHydratorInstance(){
        $result = parent::getHydratorInstance();
        $result->setNamingStrategy(new MapperNamingStrategy($this->mappingStrategy));
        $result->addFilter('non_persistent_fields', function($property){return !in_array($property,['pan','cvv','expiry','secret']);}, $condition = FilterComposite::CONDITION_AND);
        return $result;
    }

    /**
     * {@inheritDoc}
     *
     */
    protected function getDataHydratorInstance(){
        //return $this->getHydratorInstance();
        $result = parent::getDataHydratorInstance();
        $result->setNamingStrategy(new MapperNamingStrategy([
                                   'lastresponsecode' => 'lastresponse',
                                   'transactiontype' => 'transactionType',
                                   ]));
        return $result;
    }

    /**
     * {@inheritDoc}
     *
     */
    protected function fetchAllGetHydratorInstance(){
        $hydrator = parent::fetchAllGetHydratorInstance();
        $hydrator->setNamingStrategy(new MapperNamingStrategy([
                                   'lastresponsecode' => 'lastresponse',
                                   'transactiontype' => 'transactionType'
                                   ]));
        return $hydrator;
    }


    /**
     * {@inheritDoc}
     *
     */
    protected function fetchGetHydratorInstance(){
        $hydrator = parent::fetchGetHydratorInstance();
        $hydrator->setNamingStrategy(new MapperNamingStrategy([
                                   'lastresponsecode' => 'lastresponse',
                                   'transactiontype' => 'transactionType'
                                   ]));
        return $hydrator;
    }


    /**
     * {@inheritDoc}
     *
     */
    protected function fetchAllBuildQuerySetFilters($filters,&$query){
/*        $tokens = reset(array_keys($this->joinTableNames['tokens']));

        $query->join($this->joinTableNames['tokens'],
                     $this->tableAliasName.'.idcreditcard = '.$tokens.'.idcreditcard',
                     ['token'],
                     $query::JOIN_LEFT
                     );*/
// print_r((array)$filters);

        if (is_array((array)$filters))
            $query->where((array)$filters);

        return $query;
    }

    protected function fetchAllGetEntityInstance(){
        $entity = parent::fetchAllGetEntityInstance();
        /*unset($entity->cvv);
        unset($entity->pan);
        unset($entity->expiry);
        unset($entity->secret);
        unset($entity->token);*/
        return $entity;
    }

    /**
     * {@inheritDoc}
     *
     * @param  array|int $id identifier values for transactionid (assumed when $id is scalar) and transactiontype
     * this param is expected to be one of both: 132423412 or array(132423412,"Q");
     * @return Openy\Model\Transaction\TransactionEntity Last occurrence for transaction ordered by last created date
     */
    protected function fetchBuildQuerySetWhere($id,&$query){
/*        $tokens = reset(array_keys($this->joinTableNames['tokens']));

        $query->join($this->joinTableNames['tokens'],
                     $this->tableAliasName.'.idcreditcard = '.$tokens.'.idcreditcard',
                     ['token'],
                     $query::JOIN_LEFT
                     );*/

        if (!is_null($id)){
            if (is_array($id)){
                        //TODO: Hydrator recommended to predigest $id array
                $query->where(array($this->primary=>$id));

            }
            else
                $query = parent::fetchBuildQuerySetWhere($id,$query);
        }
        $query  ->order('transactionid, created DESC')
        ->limit(1);
        return $query;
    }

    protected function fetchGetEntityInstance(){
        $entity = parent::fetchGetEntityInstance();
     /*   unset($entity->cvv);
        unset($entity->pan);
        unset($entity->expiry);
        unset($entity->secret);
        unset($entity->token);*/
        return $entity;
    }

        public function insert($data){
            $result = parent::insert($data);
//            return $result;
            if ($result->{$this->primary}){
                if (isset($data->token))
                    $result->token = $data->token;
                if (isset($data->secret))
                    $result->secret = $data->secret;
            }
            return $result;
        }


        public function update($id, $data){
            $result = parent::update($id,$data);
            if (is_array($id)){
                if ($result->{$this->primary} == $id[$this->primary]){
                    if (isset($data->token))
                        $result->token = $data->token;
                    if (isset($data->secret))
                        $result->secret = $data->secret;
                }
            }
            else{
                if ($result->{$this->primary} == $id){
                    if (isset($data->token))
                        $result->token = $data->token;
                    if (isset($data->token))
                        $result->secret = $data->secret;
                }
            }

            return $result;
        }



        /*
         * {@inheritDoc}
         *
         */
        protected function updateGetHydratorInstance(){
            $hydrator = parent::updateGetHydratorInstance();
            $hydrator->addStrategy('updated', new CurrentTimestampStrategy('Y-m-d H:i:s'));
            $hydrator->addFilter('primary_key',function($property){return $property != $this->primary;},FilterComposite::CONDITION_AND);
            $hydrator->addFilter('read_only_fields',function($property){return !in_array($property,explode(',','merchantcode,idcreditcard,created,terminal'));},FilterComposite::CONDITION_AND);
            $hydrator->addFilter('calculated_fields', function($property){return !in_array($property,['token']);}, $condition = FilterComposite::CONDITION_AND);
            $hydrator->addFilter('non_persistent_fields', function($property){return !in_array($property,['pan','cvv','expiry','secret','lasterror']);}, $condition = FilterComposite::CONDITION_AND);

            return $hydrator;
        }

        /*
         * {@inheritDoc}
         *
         */
        protected function updateBuildSQLSetValues($data){
            $data->updated = isset($data->updated)? : null; // A strategy has been set for application
            return parent::updateBuildSQLSetValues($data);
        }

        /**
         * [updateBuildSQLSetWhere description]
         * @param  [type] $id      [description]
         * @param  [type] &$update [description]
         * @return [type]          [description]
         */
        protected function updateBuildSQLSetWhere($id,&$update,$data){
            $primary = is_array($id) ? $id[$this->primary] : $id;
            $update = parent::updateBuildSQLSetWhere($primary,$update,$data);
            if (is_array($id))
                $update->where($id);
            return $update;
        }



        /*
         * {@inheritDoc}
         *
         */
        protected function insertGetHydratorInstance(){
            $hydrator = parent::insertGetHydratorInstance();
            $hydrator->addStrategy('created', new CurrentTimestampStrategy('Y-m-d H:i:s'));
            $hydrator->addFilter('read_only_fields',function($property){return !in_array($property,explode(',','updated'));},FilterComposite::CONDITION_AND);
            $hydrator->addFilter('calculated_fields', function($property){return ($property!=='token');}, $condition = FilterComposite::CONDITION_AND);
            $hydrator->addFilter('non_persistent_fields', function($property){return !in_array($property,['pan','cvv','expiry','secret','lasterror']);}, $condition = FilterComposite::CONDITION_AND);
            return $hydrator;
        }


    /**
     * Method NOT ALLOWED
     *
     * {@inheritDoc}
     *
     */
    public function delete($id){
      return new ApiProblem(405, 'The delete method is not allowed');
  }


    /**
     * Method NOT ALLOWED
     *
     * {@inheritDoc}
     *
     */
    public function replace($id,$data){
      return new ApiProblem(405, 'The replace method is not allowed');
  }

}