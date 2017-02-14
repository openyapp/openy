<?php
namespace Openy\Traits\Classes;

trait CreditCardDataTrait
{


	protected $pan;
	protected $cvv;
	protected $year;
	protected $month;
	protected $token;
	protected $cardusername;
	protected $transactionid;
	public $idcreditcard;
	public $modified;


	public function __set($property,$value){
		if (property_exists(__CLASS__, $property))
			$this->property = $value;
		return $this;
	}

	public function getIdCreditCard(){
		return $this->idcreditcard;
	}

	public function getCardUsername(){
		return $this->cardusername;
	}

	public function getPan(){
		return $this->pan;
	}

	public function getCVV(){
		return $this->cvv;
	}
	public function getYear(){
		return (property_exists($this, 'cardexpyear') ? $this->cardexpyear : $this->year);
	}

	public function getMonth(){
		return (property_exists($this, 'cardexpmonth') ? $this->cardexpmonth : $this->month);
	}

	public function getToken(){
		return $this->token;
	}

	public function getModified(){
		return $this->modified;
	}

}