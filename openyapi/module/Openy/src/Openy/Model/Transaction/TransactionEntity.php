<?php

namespace Openy\Model\Transaction;

//use Openy\Model\AbstractEntity;
use Openy\Model\Classes\MessageEntity;
use Openy\Interfaces\Classes\CreditCardDataInterface;

class TransactionEntity //extends AbstractEntity
{

	const TRANSACTION_TYPE_AUTH = '0';
	const TRANSACTION_TYPE_PREAUTH = 'O';
	const TRANSACTION_TYPE_CANCEL_PREAUTH = 'Q';
	const TRANSACTION_TYPE_CONFIRM_PREAUTH = 'P';
	const TRANSACTION_TYPE_REFUND_AUTH = '3';
	const TRANSACTION_TYPE_AUTENTICATE = '7';


	public function __construct($transactionid,$merchantcode=null,$terminal=null,$secret=null,$amount=null,CreditCardDataInterface $creditcard=null,$transactiontype=TransactionEntity::TRANSACTION_TYPE_PREAUTH){
		$this->transactionid = $transactionid;
		$this->transactionType = $transactiontype;
		$this->amount = $amount;
		$this->merchantcode = $merchantcode;
		$this->terminal = $terminal;
		$this->secret = $secret;
		if ($creditcard)
			$this->setCardData($creditcard);
		$this->lasterror = new MessageEntity;
	}

	public $transactionid;
	public $authorizationcode;
	public $merchantcode;
	public $amount;
	public $idcreditcard;
	public $transactionType;
	public $created;
	public $updated;
	//public $lastresponsecode;
	public $lastresponse;
	public $lastcode;
	public $terminal;
	public $token;
	public $cvv;
	public $pan;
	public $expiry;
	public $secret;
	/**
	 * Message struct (php class) detailing the last acquired error
	 * @var \Openy\Model\Classes\MessageEntity
	 */
	public $lasterror;


	public function setCardData(CreditCardDataInterface $creditcard){
		$this->idcreditcard = $creditcard->getIdCreditCard();
		$this->cvv = $creditcard->getCVV();
		$this->pan = $creditcard->getPan();
		$this->expiry = $creditcard->getYear().$creditcard->getMonth();
		$this->token = $creditcard->getToken();
		return $this;
	}

}