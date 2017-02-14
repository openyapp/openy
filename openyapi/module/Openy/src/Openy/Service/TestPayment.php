<?php

namespace Openy\Service;

use Openy\Model\Order\OrderEntity;
use Openy\Model\Payment\PaymentEntity;
use Openy\V1\Rest\Receipt\ReceiptEntity;

use Openy\Interfaces\Service\PaymentInterface as PaymentServiceInterface;
use Openy\Traits\ServiceTrait;

class TestPayment implements PaymentServiceInterface
{

	use ServiceTrait{
		__construct as serviceTraitConstruct;
	}

	public function getOrderAuthorization(OrderEntity $order){
		return true;
	}

	public function cancellOrderAuthorization(OrderEntity $order){
		return true;
	}

	public function getOrderPayment(OrderEntity $order){
		$payment = new PaymentEntity();
		$payment->idpayment = rand();
		$payment->iduser = $order->iduser;
		$payment->paymentmethod = $order->paymentmethod;
		return $payment;
	}

	public function getReceipt(PaymentEntity $payment){
		$receipt = new ReceiptEntity();
		return $receipt;
	}

	public function getInvoice(PaymentEntity $payment){
		return null;
	}



}