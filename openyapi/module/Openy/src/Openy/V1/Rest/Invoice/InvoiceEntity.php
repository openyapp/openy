<?php
namespace Openy\V1\Rest\Invoice;

use Openy\Model\Invoice\ReceiptsInvoiceEntity as ParentEntity;

class InvoiceEntity
    extends ParentEntity
{
    const sample =  <<<HEREDOC
    {
        "idinvoice": "sample",
        "idinvoicer": "1",
        "iduser": "sample",
        "invoicenumber": "89",
        "billingdata": {
            "billingName": "Openy Fake Station",
            "billingAddress": "Av. Icaria 08000 Barcelona Spain",
            "billingId": "00000000-T",
            "billingWeb": null,
            "billingLogo": "meroil",
            "billingMail": null,
            "billingPhone": null
        },
        "summary": {
            "total": 27,
            "saving": 1.14,
            "products": {
                "GOA": 25.23
            },
            "taxes": {
                "1": {
                    "name": "IVA",
                    "locale": "es_ES",
                    "percent": "21",
                    "amount": 4.59,
                    "base": 22.31
                },
                "2": {
                    "name": "IVA (Canarias)",
                    "locale": "es_ES",
                    "percent": "15",
                    "amount": 0.0,
                    "base": 0.0
                }
            }
        },
        "created": "2015-10-21 17:56:02",
        "date": "2015-11-20",
        "_links": {
            "self": {
                "href": "http:///invoice/sample"
            }
        },
        "_embedded": {
            "receipts": [
                {
                    "idcompany":2,
                    "receiptposid": "f80958a1-c037-530d-a95a-33f0e7079c08",
                    "idopystation": "1",
                    "receiptid": "sample",
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
                            "href": "http:///receipts/sample"
                        }
                    }
                }
            ]
        }
    }
HEREDOC;
}
