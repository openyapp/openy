<?php

namespace Openy\Model\Payment;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Delete;
use Openy\Model\AbstractMapper;
use Openy\Model\Hydrator\Strategy\UuidStrategy;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;

class PaymentMapper extends AbstractMapper
{

    protected $tableName      = 'opy_payment';
    protected $primary        = 'idpayment';
    //protected $tableAliasName ;//= substr('tablename',0,3);
    protected $entity         = 'Openy\Model\Payment\PaymentEntity';
    protected $collection     = 'Zend\Paginator\Paginator';
    protected $joinTableNames = array('transactions'=>array('otrans'=>'opy_tpv_payment'),);




        protected function fetchAllBuildQuery($filters){
            $query = parent::fetchAllBuildQuery($filters);
            $query->columns([$query::SQL_STAR]);

            $transactions = reset(array_keys($this->joinTableNames['transactions']));
            $query  ->join($this->joinTableNames['transactions'],
                           $this->tableAliasName.'.idpayment = '.$transactions.'.idpayment',
                           ['transactionid'],
                           $query::JOIN_LEFT);
            return $query;
        }

        protected function fetchBuildQuery($id){
            $query = parent::fetchBuildQuery($id);
            $query->columns([$query::SQL_STAR]);

            $transactions = reset(array_keys($this->joinTableNames['transactions']));
            $query  ->join($this->joinTableNames['transactions'],
                           $this->tableAliasName.'.idpayment = '.$transactions.'.idpayment',
                           ['transactionid'],
                           $query::JOIN_LEFT);
            return $query;
        }




    public function insert($data){
        return parent::insert($data);
        $entity = $this->fetchByData($data);

        $data->{$this->primary} = isset($data->{$this->primary}) ? $data->{$this->primary} : null;

        //TODO: DISCUSS THIS BEHAVIOUR
        if (($entity->{$this->primary} == $data->{$this->primary}) &&
            isset($this->options->replace_when_insert) &&
            $this->options->replace_when_insert)
            return $this->replace($data->{$this->primary},$data);

        $insert = $this->insertBuildSQL($data);
        $driverResult = $this->insertStatementExecute($insert);
        $result = ($driverResult->getGeneratedValue() || $driverResult->getAffectedRows());
        //TODO: Test following code since return
        //TODO: $this->primary has to be replaced with resulting name from hydrator Naming Strategy
        if ($result){
            $entity = $data;
            //$entity->{$this->primary}=$this->adapterMaster->getDriver()->getLastGeneratedValue();
        }
        return $entity;
    }



    /**
     * {@inheritDoc}
     * If provided a transaction identifier, is bound to payment
     */
    public function update($id, $data){
        if (property_exists($data, 'transactionid') && !is_null($data->transactionid)):
             $insert = new Insert($this->joinTableNames['transactions']['otrans']);
                 $insert->values(['idpayment'=>$id,'transactionid'=>$data->transactionid]);
             $driverResult = $this->insertStatementExecute($insert);
             $result = ($driverResult->getAffectedRows());
        endif;
        //TODO: Decide how to proceed if $result cannot be evaluated as TRUE
        return parent::update($id,$data);
    }

    protected function updateGetHydratorInstance(){
        $hydrator = parent::updateGetHydratorInstance();
        $hydrator->addFilter('outer_properties',function($property){return !in_array($property,['transactionid']); },FilterComposite::CONDITION_AND);
        $hydrator->addFilter('primary_key',function($property){return $property!=$this->primary;},FilterComposite::CONDITION_AND);
        return $hydrator;
    }


    protected function insertGetHydratorInstance(){
    	$hydrator = parent::insertGetHydratorInstance();
    	$hydrator -> addStrategy('idpayment',new UuidStrategy($this->options));
        $hydrator->addFilter('outer_properties',function($property){return !in_array($property,array('transactionid',)); },FilterComposite::CONDITION_AND);
    	return $hydrator;
    }

}