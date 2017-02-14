<?php
namespace Openy\Model\Invoice;

use Openy\Model\Invoice\InvoiceEntity as ParentEntity;

class ReceiptsInvoiceEntity
    extends ParentEntity
{

    public $receipts; // ReceiptCollection


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
        },
        "receipts": [
            {
                "idcompany":2,
                "receiptposid": "f80958a1-c037-530d-a95a-33f0e7079c08",
                "idopystation": "1",
                "receiptid": "63",
                "summary": {
                    "data": "SERIALIZED DATA",
                    "details": {
                        "Fecha": "30/10/2015 11:11",
                        "Precio/lt": "1,190",
                        "Litros": "22,23",
                        "Precio": "21,86€",
                        "IVA": "4,59€",
                        "Total": "26.45€",
                        "Ahorro": "1.14€"
                    }
                },
                "billingdata": {
                    "billingName": "Openy Fake Station",
                    "billingAddress": "Av. Icaria 08000 Barcelona Spain",
                    "billingId": "00000000-T",
                    "billingWeb": null,
                    "billingLogo": "meroil",
                    "billingMail": null,
                    "billingPhone": null
                },
                "date": "2015-10-30 11:11:03",
                "_links": {
                    "self": {
                        "href": "http:///receipts/63"
                    }
                }
            }
        ]
    }
HEREDOC;
}
