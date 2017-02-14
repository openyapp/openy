<?php
namespace Openy\V1\Rest\Receipt;

use Openy\Model\Payment\ReceiptEntity as ParentEntity;

class ReceiptEntity
	extends ParentEntity
{
	public function __construct(){
		unset($this->receiptposid);
		unset($this->taxes);
		unset($this->amount);
		unset($this->template);
		unset($this->idpayment);
		//unset($this->idopystation);
	}

    public function __set($property,$value){
        if (property_exists(__CLASS__, $property))
            $this->property = $value;
        return $this;
    }
}
