<?php

namespace Openy\Model\Order;

class OrderStatusEntity
{
	const STATUS_NOT_EXISTING = 0; // Order does not exist
	const STATUS_CANCELLED = 1; // Cancelled by user
	const STATUS_ORDERED = 2; // Order is registered in Openy
	const STATUS_AUTHORIZED = 3; // Authorized by Openy to be delivered/served
								 // e.g. User have enough bank account balance
								 // e.g.2 User have enough Openy vouchers
	const STATUS_ISSUED = 4; // Order has been issued for delivery
	const STATUS_DELIVERED = 4; // Order has been delivered
	const STATUS_PAYED = 5;
	const STATUS_INVOICED = 6;

	/**
	 * Status id, listed in this class constants
	 * @var integer
	 */
	public $status;

	/**
	 * Order id owner of this instance
	 * @var integer
	 */
	public $idorder;

	/**
	 * Transaction for order authorization and payment
	 * @var string
	 */
	public $paymentoperationid;

	/**
	 * Last operation response code obtained when requesting given transaction
	 * @var string
	 */
	public $lastresponse;

	/**
	 * Last operation code obtained when requesting given transaction
	 * @var string
	 */
	public $lastcode;

	/**
	 * Explanatory error message associated to operation and response codes
	 * @var string
	 */
	public $codemsg;

	/**
	 * Explanatory error message associated by openy to operation and response codes
	 * @var string
	 */
	public $openymsg;

	public function __construct($status = self::STATUS_ORDERED){
		$this->status = $status;
	}

	public function __toInt(){
		return (int)$this->status;
	}

}