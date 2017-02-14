<?php

namespace Openy\Model\Order;

use Openy\Model\AbstractEntity;
use Openy\Model\Order\OrderStatusEntity as OrderStatus;
class OrderEntity
	extends AbstractEntity
{
	const STATUS_NOT_EXISTING = OrderStatus::STATUS_NOT_EXISTING; // Order does not exist
	const STATUS_CANCELLED = OrderStatus::STATUS_CANCELLED; // Cancelled by user
	const STATUS_ORDERED = OrderStatus::STATUS_ORDERED; // Order is registered in Openy
	const STATUS_AUTHORIZED = OrderStatus::STATUS_AUTHORIZED; // Authorized by Openy to be delivered/served
								 // e.g. User have enough bank account balance
								 // e.g.2 User have enough Openy vouchers
	const STATUS_ISSUED = OrderStatus::STATUS_ISSUED;
	const STATUS_DELIVERED = OrderStatus::STATUS_DELIVERED; // Order has been delivered / served
	const STATUS_PAYED = OrderStatus::STATUS_PAYED;
	const STATUS_INVOICED = OrderStatus::STATUS_INVOICED;

	public $idorder;
	public $idopystation;
	public $summary;
	public $amount;
	public $iduser;
	public $idpayment;
	public $paymentmethod;
	public $deliverycode;
	public $paymentmethodid;
	/**
	 * Status as embedded entity.
	 * Refer to its status property in order to solve it
	 * @var Openy\Model\Order\OrderStatusEntity
	 */
	public $orderstatus;
	public $created;
	public $updated;

  public $receiptid;

    const pk = 'idorder';
    const sample =  <<<HEREDOC
{
  "idorder": "350",
  "idopystation": "1",
  "summary": {
    "data": "SERIALIZED DATA",
    "details": {
      "Fecha": "02/02/2015 18:00",
      "Precio/lt": "1.190",
      "Litros": "25.23",
      "Precio": "22.31€",
      "Total": "27€",
      "Ahorro": "1.14",
      "IVA": "21%",
      "IVAAmount": "4.59€",
      "Product": "GOA"
    }
  },
  "amount": "26.45",
  "iduser": "4c3df859-d90f-5152-8a2e-31ee9a183998",
  "idpayment": "591628c3-5fa5-5034-9294-ef60ea22d0a9",
  "paymentmethod": "1",
  "deliverycode": "0da9af710a519ac7c77d4d7a9a2ef68f",
  "paymentmethodid": "38e8d74f-7588-5abd-a3f4-0f86902972da",
  "orderstatus": "6",
  "created": "2015-11-19 21:20:55",
  "updated": "2015-11-19 21:20:56",
  "_links": {
    "self": {
      "href": "https:///orders/350"
    }
  }
}
HEREDOC;
}