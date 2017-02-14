<?php
namespace Openy\Model\Payment;


class PaymentEntity
{
	public $idpayment;
	public $iduser;
	public $idpaymentmethod;
	// TODO extend this along with DB model
	// Attributes from Join
	public $transactionid;
}