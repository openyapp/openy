<?php
namespace Openy\Model\Creditcard;

use Openy\Model\AbstractEntity;
use Openy\Model\Classes\CreditCardDataEntity;

class CreditcardEntity extends CreditCardDataEntity
{
	// App values
	//Primary Key
	public $idcreditcard;
	public $cardusername;
	public $pan;

	public $cardexpyear; //unset in API entity
	public $cardexpmonth;//unset in API entity
	public $transactionid;//unset in API entity
	public $created;//unset in API entity
	public $updated;//unset in API entity

	// CALCULATED VALUES
	public $validated;
	public $expires;
	public $favorite;
	public $active;
}