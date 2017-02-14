<?php
namespace Openy\Model\Creditcard;

use Openy\Model\Creditcard\CreditcardEntity;

class ValidatedCreditcardEntity extends CreditcardEntity
{
	public $validator;
	public $token;
	public $transactionid;
	// $validated <- inherited from CreditCardEntity
}