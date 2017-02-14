<?php

namespace Openy\Model\Tpv\SOAP;

use Openy\Model\AbstractMapper;
use Openy\Model\Tpv\SOAP\RequestEntity as Request;
use Openy\Model\Hydrator\Strategy\CurrentTimestampStrategy;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;

use Openy\Model\Transaction\TransactionEntity as Transaction;
//use Openy\Model\CreditCard\CreditCardInterface;

class RequestMapper
	extends AbstractMapper
{

    protected $tableName      = 'opy_tpv_soap_request';
    protected $primary        = 'idsoaprequest';
    protected $secondary      = ['transactionid','transactionType'];
    //protected $tableAliasName ;//= substr('tablename',0,3);
    protected $entity         = 'Openy\Model\Tpv\SOAP\RequestEntity';
    protected $collection     = 'Zend\Paginator\Paginator';
    protected $joinTableNames = array('transactions'=>array('otrans'=>'opy_tpv_transaction'),);
    protected $mappingStrategy = ['transactiontype' => 'transactionType',];
    
    protected function fetchGetHydratorInstance(){
        $result = parent::fetchGetHydratorInstance();
        $result->setNamingStrategy(new MapperNamingStrategy($this->mappingStrategy));
        return $result;
    }
    
    public function locate($entity){
        $old_primary = $this->primary;
        // TODO AbstractMapper must be capable to locate requests by more than one column
        // such as transactionid and transactiontype
        if ($entity instanceof Request){
            if (!is_null($entity->transactionid) && is_null($entity->{$this->primary}))
                $this->primary = $this->secondary;
        }
        $result = parent::locate($entity);
        $this->primary = $old_primary;
        return $result;
    }


        protected function fetchAllBuildQuery($filters){
            $query = parent::fetchAllBuildQuery($filters);
            $query->columns([$query::SQL_STAR]);

            $transactions = reset(array_keys($this->joinTableNames['transactions']));
            $query  ->join($this->joinTableNames['transactions'],
                           $this->tableAliasName.'.transactionid = '.$transactions.'.transactionid'
                            .' AND '
                            .$this->tableAliasName.'.transactiontype = '. $transactions.'.transactiontype',
                           ['merchantcode','amount','idcreditcard',/*WHAT ABOUT TOKEN¿??*/],
                           $query::JOIN_INNER);
            return $query;
        }

        protected function fetchBuildQuery($id,$where = []){
            $query = parent::fetchBuildQuery($id,$where);

            $query->columns([$query::SQL_STAR]);

            $transactions = reset(array_keys($this->joinTableNames['transactions']));
            $query  ->join($this->joinTableNames['transactions'],
                           $this->tableAliasName.'.transactionid = '.$transactions.'.transactionid'
                            .' AND '
                            .$this->tableAliasName.'.transactiontype = '. $transactions.'.transactiontype',
                           ['merchantcode','amount','idcreditcard',/*WHAT ABOUT TOKEN¿??*/],
                           $query::JOIN_INNER);
            return $query;
        }

            protected function fetchBuildQuerySetWhere($id,&$query,$where=[]){
                $columns = array_keys($where);
                $columns = array_map(function($column){
                            return (strpos($column, $this->tableAliasName)===0)?$column : $this->tableAliasName.'.'.$column;},
                            $columns);
                $where = array_combine($columns, $where);
                return parent::fetchBuildQuerySetWhere($id,$query,$where);
            }


		protected function insertGetHydratorInstance(){
            $hydrator = parent::insertGetHydratorInstance();
            $hydrator->addStrategy('sent', new CurrentTimestampStrategy('Y-m-d H:i:s'));
            $hydrator->addFilter('primary_key',function($property){return $property != $this->primary;},FilterComposite::CONDITION_AND);
            $hydrator->addFilter('joined_attributes',function($property){
            		return ($property == 'transactionid'
            		        ||
                            $property == 'transactionType'
                            ||
            		        !in_array($property,array_keys(get_class_vars('Openy\Model\Transaction\TransactionEntity'))));}
            		        ,FilterComposite::CONDITION_AND);
            $hydrator->addFilter('fixed_values', function($property){return !in_array($property,['currency']);},FilterComposite::CONDITION_AND);
            return $hydrator;
        }

        protected function updateGetHydratorInstance(){
            $hydrator = parent::updateGetHydratorInstance();
            $hydrator->addFilter('updatable_fields',function($property){return $property=='data';},FilterComposite::CONDITION_AND);
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
	public function update($id,$data){
        return parent::update($id,$data);
    	return new ApiProblem(405, 'The update method is not allowed');
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