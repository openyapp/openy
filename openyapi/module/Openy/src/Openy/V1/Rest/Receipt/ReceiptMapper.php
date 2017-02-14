<?php

namespace Openy\V1\Rest\Receipt;

use Openy\Model\Payment\ReceiptMapper as ParentMapper;
use Openy\V1\Rest\Receipt\ReceiptHydrator;

class ReceiptMapper
	extends ParentMapper
{

	protected $collection     = 'Openy\V1\Rest\Receipt\ReceiptCollection';
	protected $entity         = 'Openy\V1\Rest\Receipt\ReceiptEntity';
    protected $hydrator       = 'Zend\Stdlib\Hydrator\Reflection';
    protected $dataHydrator   = 'Zend\Stdlib\Hydrator\ObjectProperty';
    protected $tableAliasName = 'receipts';
    // do not remove or alter this Alias before fixing Zend DB Select Join bug
    // what consists in not escaping table aliases such SQL keywords on JOIN "ON" SENTENCE

	protected function getHydratorInstance(){
        $hydrator = new ReceiptHydrator($this->options);
        if ($hydrator->hasStrategy('receiptposid'))
            $hydrator->removeStrategy('receiptposid');
        if ($hydrator->hasFilter('NON_IMPLEMENTED_PROPERTIES'))
            $hydrator->removeFilter('NON_IMPLEMENTED_PROPERTIES');
        $hydrator->removeStrategy('taxes');
        return $hydrator;
    }

        protected function fetchAllBuildQuery($filters){
            $query = parent::fetchAllBuildQuery($filters);
            $query->order($this->tableAliasName.'.date DESC');
            return $query;
        }

        protected function fetchAddFilterByUser(&$query){
            $user = $this->currentUser->getUser();
            $query  ->join(
                     array('payments'=>'opy_payment'),
                     $this->tableAliasName.'.idpayment = payments.idpayment',
                     array(),
                     $query::JOIN_INNER
                     )
                    ->where(array('payments.iduser'=>$user['iduser']));
            return $query;
        }

        protected function fetchAllBuildQuerySetFilters($filters,&$query){
            parent::fetchAllBuildQuerySetFilters($filters,$query);
            $query = $this->fetchAddFilterByUser($query);
            if (count($filters)){
                if (isset($filters['until'])){
                    $hydr  = $this->fetchAllGetHydratorInstance();
                    $strat = $hydr->getStrategy('date');
                    $date  = date_create($filters['until']);
                    $date  = $date->add(new \DateInterval('P1D'));
                    $date  = $date->format($strat->getFormat());
                    $query ->where($this->tableAliasName.".date < '".$date."'");
                }
                if (isset($filters['idorder'])){
                    $query->join(
                            array('orders'=>'opy_order'),
                            $this->tableAliasName.'.idpayment = orders.idpayment',
                            array(),
                            $query::JOIN_INNER
                        );
                    $query ->where('orders.idorder = '.filter_var($filters['idorder'],FILTER_VALIDATE_INT,['options'=>['default'=>0]]));
                }
            }
            return $query;
        }

        protected function fetchBuildQuerySetWhere($id,&$query,$where=[]){
            parent::fetchBuildQuerySetWhere($id,$query,$where);
            $query = $this->fetchAddFilterByUser($query);
            return $query;
        }

}
