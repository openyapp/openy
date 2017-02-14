<?php

namespace Openy\Model\Order;

use Openy\Model\AbstractMapper;
use Openy\Model\Hydrator\Strategy\CurrentTimestampStrategy;
use Openy\Model\Hydrator\Strategy\NullStrategy;
use Openy\Model\Hydrator\Strategy\NumericVarcharStrategy;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Serializer\Adapter\Json;
use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\Hydrator\Strategy\SerializableStrategy;

class OrderMapper extends AbstractMapper
{

    protected $tableName      = 'opy_order';
    protected $primary        = 'idorder';
    protected $secondary      = ['idpayment'/*,'deliverycode'*/];
    protected $entity         = 'Openy\Model\Order\OrderEntity';
    protected $collection     = 'Openy\Model\Order\OrderCollection';
    protected $hydrator       = 'Openy\Model\Hydrator\OrderHydrator';
    protected $joinTableNames = array('receipts'=>array('receipts'=>'opy_receipt'),);

    protected function getDataHydratorInstance(){
        $hydrator = parent::getDataHydratorInstance();
        $hydrator->addStrategy('summary', new SerializableStrategy(new Json));
        return $hydrator;
    }

    protected function getHydratorInstance(){
        $hydrator = parent::getHydratorInstance();
        $hydrator->addStrategy('summary', new SerializableStrategy(new Json));
        return $hydrator;
    }

    protected function updateBuildSQLSetValues($data){
        $data->updated = null;
        return parent::updateBuildSQLSetValues($data);
    }

    protected function insertBuildSQLSetValues(&$data, &$insert){
        $data->created = null;
        return parent::insertBuildSQLSetValues($data,$insert);
    }

    protected function updateGetHydratorInstance(){
        $hydrator = parent::updateGetHydratorInstance();
        $hydrator->addStrategy('amount', new NumericVarcharStrategy(2,'.'));
        $hydrator->addStrategy('updated', new CurrentTimestampStrategy('Y-m-d H:i:s'));
        $hydrator->addFilter('readonly',function($property){return !in_array($property,array('idorder','idopystation','iduser','created','receiptid')); });
                   //TODO: to be improved, cause properties such orderstatus may be modifiable depending on some complex criteria
        return $hydrator;
    }

    protected function insertGetHydratorInstance(){
        $hydrator = parent::insertGetHydratorInstance();
        $hydrator->addStrategy('amount', new NumericVarcharStrategy(2,'.'));
        $hydrator->addStrategy('created', new CurrentTimestampStrategy('Y-m-d H:i:s'));
        $hydrator->addStrategy('updated', new NullStrategy());
        $hydrator->addFilter('readonly',function($property){return !in_array($property,array('idorder','updated','receiptid')); });
        return $hydrator;
    }

    protected function fetchAllBuildQuery($filters){
        $query = parent::fetchAllBuildQuery($filters);
        $receipts = reset(array_keys($this->joinTableNames['receipts']));
        $query  ->join($this->joinTableNames['receipts'],
                        new \Zend\Db\Sql\Expression(
                            'IFNULL('.$this->tableAliasName.'.idpayment,\'--\') = '.$receipts.'.idpayment'
                        ),
                       ['receiptid'],
                       $query::JOIN_LEFT);
        return $query;
    }

    protected function fetchBuildQuery($filters){
        $query = parent::fetchBuildQuery($filters);
        $receipts = reset(array_keys($this->joinTableNames['receipts']));
        $query  ->join($this->joinTableNames['receipts'],
                       new \Zend\Db\Sql\Expression(
                            'IFNULL('.$this->tableAliasName.'.idpayment,\'--\') = '.$receipts.'.idpayment'
                        ),
                       ['receiptid'],
                       $query::JOIN_LEFT);
        return $query;
    }

}