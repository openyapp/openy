<?php
namespace Openy\V1\Rest\Creditcard;

use Openy\Model\Creditcard\CreditcardEntity as ParentEntity;

class CreditcardEntity extends ParentEntity
{		

	public function __construct(){
		unset($this->cardexpyear);
		unset($this->cardexpmonth);
		unset($this->created);
		unset($this->updated);
		unset($this->transactionid);
	}
}
