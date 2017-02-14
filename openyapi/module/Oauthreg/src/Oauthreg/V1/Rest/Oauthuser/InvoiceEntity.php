<?php

class InvoiceEntity
{
    public $iduser;
    public $idorder;
    public $idpayment;
    public $paymentmethod;
    public $paymentmethodid;
    public $deliverycode;
    public $idreceipt;
    public $idinvoice;
    public $amount;
    public $created;
    
    private $turno;
    private $terminal;
    private $albaran;
  
}




{
    "iduser": "f7914e2b-b903-57cb-9b37-2063ea0ed1c8",
    "date": "2015/11/05 18:27",
    "invoicedata": {
        "invoiceName": "Openy Fake Station",
        "invoiceAddress": "Av. Icaria 08000 Barcelona Spain",
        "invoiceId": "00000000-T",
        "invoiceWeb": null,
        "invoiceLogo": "meroil",
        "invoiceMail": null,
        "invoicePhone": null,
        "invoiceDate": "2015/11/05 18:27",    
    },    
    "details": {
        "Total": "suma de totales de tickets",
        "Ahorro": "suma de ahorros"
        "taxes": {
            "1": {
            "name": "IVA",
            "locale": "es_ES",
            "percent": "21"
            "amount": "suma de totales"
            "base": "suma de precios"            
        },
         "2": {
            "name": "IVA (Canarias)",
            "locale": "es_ES",
            "percent": "15"
            "amount": "suma de totales"
            "base": "suma de precios"
        }        
}
"Products":{
  "GOA": "SUMA DE LITROS consumidos",
  " G95": "SUMA de litros consumidos,
  " G98": "SUMA de litros consumidos"
     }    
    "created": "2015-10-21 17:56:02",
    "_embedded": {        
        "receipts": [           
                    {
                        "idcompany":"2",
                        "receiptposid": "f80958a1-c037-530d-a95a-33f0e7079c08",
                        "idopystation": "1"
                        "receiptid": "63",
                        "summary": {
                          "data": "SERIALIZED DATA",
                          "details": {
                            "Fecha": "30/10/2015 11:11",
                            "Precio/lt": "1,190",
                            "Litros": "22,23",
                            "Precio": "21,86€",
                            "Tax": "21%",
                            "TaxAmmount": "4,59€",
                            "Product":"GOA",
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
                    },
            
                    {
                        "idcompany":"2",
                        "receiptposid": "f80958a1-c037-530d-a95a-33f0e7079c08",
                        "idopystation": "1"
                        "receiptid": "63",
                        "summary": {
                          "data": "SERIALIZED DATA",
                          "details": {
                            "Fecha": "30/10/2015 11:11",
                            "Precio/lt": "1,190",
                            "Litros": "22,23",
                            "Precio": "21,86€",
                            "Tax": "21%",
                            "TaxAmmount": "4,59€",
                            "Product":"GOA",
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
                    },
                ]
    },
    "_links": {
        "self": {
            "href": "http:///refuel"
        }
    }
}