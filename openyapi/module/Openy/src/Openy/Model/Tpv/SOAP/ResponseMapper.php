<?php

namespace Openy\Model\Tpv\SOAP;

use Openy\Model\AbstractMapper;
//use Openy\Model\Tpv\SOAP\RequestEntity;
//use Openy\Model\Transaction\TransactionEntity;
//use Openy\Model\CreditCard\CreditCardInterface;
use Openy\Model\Hydrator\Strategy\CurrentTimestampStrategy;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;


class ResponseMapper
	extends AbstractMapper
{

    protected $tableName      = 'opy_tpv_soap_response';
    protected $primary        = 'idsoaprequest';
    //protected $tableAliasName ;//= substr('tablename',0,3);
    protected $entity         = 'Openy\Model\Tpv\SOAP\ResponseEntity';
    protected $collection     = 'Zend\Paginator\Paginator';
    protected $joinTableNames = array('transactions'=>array('otrans'=>'opy_tpv_transaction'),);


        protected function fetchAllBuildQuery($filters){
            $query = parent::fetchAllBuildQuery($filters);
            $query->columns([$query::SQL_STAR]);

            $transactions = reset(array_keys($this->joinTableNames['transactions']));
            $query  ->join($this->joinTableNames['transactions'],
                           $this->tableAliasName.'.transactionid = '.$transactions.'.transactionid',
                           ['authorizationcode',/*WHAT ABOUT TOKEN¿??*/],
                           $query::JOIN_INNER);
            return $query;
        }

        protected function fetchBuildQuery($id){
            $query = parent::fetchBuildQuery($id);
            $query->columns([$query::SQL_STAR]);

            $transactions = reset(array_keys($this->joinTableNames['transactions']));
            $query  ->join($this->joinTableNames['transactions'],
                           $this->tableAliasName.'.transactionid = '.$transactions.'.transactionid',
                           ['authorizationcode',/*WHAT ABOUT TOKEN¿??*/],
                           $query::JOIN_INNER);
            return $query;
        }


		protected function insertGetHydratorInstance(){
            $hydrator = parent::insertGetHydratorInstance();
            $hydrator->addStrategy('received', new CurrentTimestampStrategy('Y-m-d H:i:s'));
            $hydrator->addFilter('non_persistent_attributes',function($property){return $property != 'token';}, FilterComposite::CONDITION_AND);
            $hydrator->addFilter('joined_attributes',function($property){return $property!='authorizationcode';}, FilterComposite::CONDITION_AND);
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



	// TODO : This kind of work have to be performed by a Service class // SOAP MAPPER
	// This service class will mount a datoEntrada from TransactionEntity and some options, and will return an instance of RequestEntity

/*

	protected function signature (TransactionEntity $Transaction, RequestEntity $SOAPRequest, CreditCardInterface $creditcard){
		extract(get_object_vars($SOAPRequest));
		// extracts $idsoaprequest,  $sent,  $data,  $url, $wsdl,  $version,  $function,  $transactionid
		extract(get_object_vars($Transaction));
		// extracts $amount, $merchantcode, $transactionid, $transactiontype, ($idcreditcard, $created, $updated, $lastresponse, $terminal)
		$merchant_code = $merchantcode ? : $this->options->getTpv()->getDefault()->getMerchant_Code();
		$currency = $this->options->getTpv()->getDefault()->getCurrency();
		$transaction_type = $transactiontype

		$pan = $creditcard->getPan();
		$cvv = $creditcard->getCvv();

		$chain = intval(round($amount*100)) . $transctionid . $merchant_code . $currency .
				  ( (strcmp($this->identifier,'REQUIRED') == 0) ? $pan . $cvv : '') .
				  $transaction_type . $identifier . $secret;

		return sha1($chain);
	}
*/
}