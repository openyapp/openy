<?php
namespace Openy\Model\Payment;

use Openy\Model\AbstractEntity;

class ReceiptEntity
    extends AbstractEntity
{
	/**
	 * Internal Id for the receipt
	 * @var Unsigned Int
	 */
	public $receiptid;
	/**
	 * External Id for the receipt. The actual number used by issuer
	 * @see \Openy\Model\Classes\BillingData billingId attribute
	 * @var String
	 */
	public $receiptposid;

	public $idinvoicer;

	/**
	 * Summary for receipt (what is billed)
	 * @var array of ["qty", "line", "amount"] where
	 * each entry is an string and qty is optional
	 */
	public $summary = [];
	/**
	 * Taxes applied to receipt
	 * @var array of \Openy\Model\Classes\BillingTax
	 */
	public $taxes = [];
	/**
	 * Amount for Receipt
	 * @var Numerical String
	 */
	public $amount;
	/**
	 * Billing data for receipt ISSUER
	 * @var \Openy\Model\Classes\BillingData
	 */
	public $billingdata;
	/**
	 * Template name, used to print the receipt
	 * @var String
	 */
	public $template;
	/**
	 * Receipt date
	 * @var Datetime
	 */
	public $date;
	/**
	 * Payment identifier
	 * @var String
	 */
	public $idpayment;
	/**
	 * Openy Station Identifier
	 * @var Int
	 */
	public $idopystation;

	/**
	 * Identifier for invoice where receipt has been included
	 * @var String
	 */
	public $idinvoice;

    /**
     * Identifier for the order behind the receipt payment
     * @var Int
     */
    public $idorder;

    const pk = 'receiptid';
    const sample =  <<<HEREDOC
{
  "receiptid": "113",
  "idinvoicer": "1",
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
  "billingdata": {
    "billingName": "Openy Fake Station",
    "billingAddress": "Av. Icaria 08000 Barcelona espiña",
    "billingId": "00000000-T",
    "billingWeb": null,
    "billingLogo": "meroil",
    "billingMail": null,
    "billingPhone": null
  },
  "date": "2015-11-19 17:22:30",
  "idopystation": "1",
  "idinvoice": "a2ad335b-b820-5684-9ca0-522459ac0a03",
  "_links": {
    "self": {
      "href": "https:///receipts/113"
    }
  }
}
HEREDOC;
}
