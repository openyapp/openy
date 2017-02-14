<?php

namespace Openy\Model\Payment;

use Openy\Model\AbstractMapper;
use Openy\Model\Classes\BillingDataEntity;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;
use Openy\Model\Hydrator\ReceiptHydrator;
use Openy\Model\Payment\ReceiptEntity as Receipt;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;

class ReceiptMapper
	extends AbstractMapper
{
    protected $tableName      = 'opy_receipt';
    protected $primary        = 'receiptid';
    protected $secondary      = ['idpayment', ['receiptposid','idopystation']];
    protected $entity         = 'Openy\Model\Payment\ReceiptEntity';
    protected $collection     = 'Openy\Model\Payment\ReceiptCollection'; //'Zend\Paginator\Paginator';
    protected $joinTableNames = [
                                'orders'=>array('orders'=>'opy_order')
    ];

    protected function getHydratorInstance(){
        return new ReceiptHydrator($this->options);
    }

    protected function updateGetHydratorInstance($data){
        $hydrator = parent::updateGetHydratorInstance($data);
        $hydrator->addFilter('updatable_fields',
                             function($property){
                                return in_array($property,['receiptposid','date','idinvoice']);
                             },
                             FilterComposite::CONDITION_AND
                             );
        return $hydrator;
    }

    protected function insertGetHydratorInstance($data){
        if ($data instanceof BillingDataEntity){
            $hydrator =  new \Zend\Stdlib\Hydrator\Reflection();
            $namingStrategy = new MapperNamingStrategy([
                                            'issuername'    => 'billingName',
                                            'issuerid'      => 'billingId',
                                            'issueraddress' => 'billingAddress',
                                            'logo'          => 'billingLogo'
                                         ]);
            $hydrator->setNamingStrategy($namingStrategy);
            $hydrator->addFilter('usefull_billing_data',
                                function($property){
                                    return in_array($property,['issuername','issuerid','issueraddress','logo']);
                                },
                                FilterComposite::CONDITION_AND
                                );
        }
        else{
            $hydrator = parent::insertGetHydratorInstance();
            $hydrator->addFilter('billing_data',function($prop){return $prop != 'billingdata';},FilterComposite::CONDITION_AND);
            $hydrator->addFilter('readonly',function($property){return !in_array($property,array('idorder'));},FilterComposite::CONDITION_AND);
        }
        return $hydrator;
    }

    /**
     * Extracts billing data subvalues and populates values for insert
     * @param  StdClass &$data   Object with billingdata property
     * @param  \Zend\Db\Sql\Insert &$insert Insert to be prepared with values
     * @return \Zend\Db\Sql\Insert Input $insert var once ready for execution
     */
    protected function insertBuildSQLSetValues(&$data, &$insert){
        $insert = parent::insertBuildSQLSetValues($data, $insert);
        $billingData = new BillingDataEntity($data->billingdata);
        // We get our own hydrator for billing data
        $hydrator = $this->insertGetHydratorInstance($billingData);
        $billingValues = $hydrator->extract($billingData);
        $insert->values($billingValues, \Zend\Db\Sql\Insert::VALUES_MERGE);
        return $insert;
    }

    protected function fetchAllBuildQuery($filters){
        $query = parent::fetchAllBuildQuery($filters);
        $orders = reset(array_keys($this->joinTableNames['orders']));
        $query  ->join($this->joinTableNames['orders'],
                        new \Zend\Db\Sql\Expression(
                            'IFNULL('.$this->tableAliasName.'.idpayment,\'--\') = '.$orders.'.idpayment'
                        ),
                       ['idorder'],
                       $query::JOIN_LEFT);
        return $query;
    }

    protected function fetchBuildQuery($filters){
        $query = parent::fetchBuildQuery($filters);
        $orders = reset(array_keys($this->joinTableNames['orders']));
        $query  ->join($this->joinTableNames['orders'],
                       new \Zend\Db\Sql\Expression(
                            'IFNULL('.$this->tableAliasName.'.idpayment,\'--\') = '.$orders.'.idpayment'
                        ),
                       ['idorder'],
                       $query::JOIN_LEFT);
        return $query;
    }

}
