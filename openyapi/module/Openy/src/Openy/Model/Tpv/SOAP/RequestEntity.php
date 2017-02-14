<?php

namespace Openy\Model\Tpv\SOAP;

use Openy\Model\Transaction\TransactionEntity;

class RequestEntity
	extends TransactionEntity
{

	public function __construct(TransactionEntity $transaction=null){
		if ($transaction){
			foreach(get_object_vars($this) as $key => $value)
				if (property_exists($transaction, $key))
					$this->$key = $transaction->$key;
		}
	}

	public $idsoaprequest;
	public $sent;
	public $data;
	public $url;
	public $wsdl;
	public $version;
	public $function;
	public $currency;
	//extended from TransactionEntity
	//public $transactionid;
	//public $authorizationcode;
	//public $merchantcode;
	//public $amount;
	//public $idcreditcard;
	//public $transactionType;
	//public $created;
	//public $updated;


}