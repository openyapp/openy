<?php
/**
 * Entity.
 * Invoicing Model Entity base class
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Invoicing
 * @category Invoices
 */


namespace Openy\Model\Invoice;

use Openy\Model\AbstractEntity;

/**
 * Openy Invoice Base Entity
 * Structures info for Invoices
 *
 * @see  Openy\Model\AbstractEntity Abstract Base Class
 *
 */
class InvoiceEntity
    extends AbstractEntity
{
    /**
     * @var String UUID
     * Invoice Internal Identifier.
     * @api UUID used as primary key
     */
    public $idinvoice;

    /**
     * @var String COMPANY
     * Invoicer identifier.
     * @see  Openy\Model\Company\CompanyEntity::idcompany IdCompany attribute
     * @internal  Company ID used for B.I. and Big Data
     */
    public $idinvoicer;

    /**
     * @var Integer NUMBER
     * Invoice (consecutive) number.
     * @category Invoice Header
     */
    public $invoicenumber;

    /**
     * @var String USER
     * Recipient identifier.
     * @category Invoice Header
     *
     * @see  Oauthreg\V1\Rest\Oauthuser::iduser IdUser attribute at Oauthuser class
     * @see  Openy\V1\Rest\Preference\PreferenceEntity PreferenceEntity User preferences for billing
     */
    public $iduser;

    /**
     * @var StdClass SUMMARY.
     * Contains invoice aggregate values, computed from Receipts compounding the Invoice
     * @category Invoice Footer
     *
     * @see  Openy\Model\Payment\ReceiptEntity::$summary Summary attribute at Receipt class
     */
    public $summary;

    /**
     * @var Openy\Model\Classes\BillingDataEntity INVOICER DATA
     * Invoicer Billing Data
     * @category Invoice Header
     *
     * @uses Openy\Model\Classes\BillingDataEntity BillingDataEntity class
     */
    public $billingdata;

    /**
     *
     * @var \DateTime STAMP
     * Invoice creation timestamp.
     * @internal  Invoice timestamp used in B.I. and Stats
     */
    public $created;

    /**
     *
     * @var \DateTime DATE
     * Invoicing date.
     * Part of the Invoice Header
     */
    public $date;

    /**
     * PK (or Identifier) attribute name for Invoice entities.
     * @const string
     */
    const pk = 'idinvoice';

    /**
     * JSON sample of InvoiceEntity.
     * @const string
     */
    const sample =  <<<HEREDOC
    {
        "idinvoice": "ghe32342-bs33-57cb-0000-25esfaed1d5",
        "iduser": "f7914e2b-b903-57cb-9b37-2063ea0ed1c8",
        "billingdata": {
            "billingName": "Openy Fake Station",
            "billingAddress": "Av. Icaria 08000 Barcelona Spain",
            "billingId": "00000000-T",
            "billingWeb": null,
            "billingLogo": "meroil",
            "billingMail": null,
            "billingPhone": null,
            "billingDate": "2015/11/05 18:27"
        },
        "summary": {
            "Total": "suma de totales de tickets",
            "Ahorro": "suma de ahorros",
            "taxes": {
                "1": {
                    "name": "IVA",
                    "locale": "es_ES",
                    "percent": "21",
                    "amount": "suma de totales",
                    "base": "suma de precios"
                },
                "2": {
                    "name": "IVA (Canarias)",
                    "locale": "es_ES",
                    "percent": "15",
                    "amount": "suma de totales",
                    "base": "suma de precios"
                }
            },
            "products":{
               "GOA": "SUMA DE LITROS consumidos",
                "G95": "SUMA de litros consumidos",
                "G98": "SUMA de litros consumidos"
            }
        },
        "created": "2015-10-21 17:56:02",
        "_links": {
            "self": {
                "href": "http:///invoice/ghe32342-bs33-57cb-0000-25esfaed1d5"
            }
        }
    }
HEREDOC;
}