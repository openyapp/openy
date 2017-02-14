# Invoices

## List all (**paginated**, **filtered** by session user and **ordered** DESC by invoicenumber)
| HTTP METHOD &amp; HEADERS |
| ---- |
| **Endpoint URL** //invoice |
| **Accept**: application/*+json, application/json |
| **Content-Type**: application/json  |
| **Authorization**: Bearer Token  |

### Query parameters
(all of them are optional)

* **page** (Unsigned Int) : *Assumed "1" by default*
* **until** (8 digits date in format "YYYYMMDD") : *Top date for invoices listed. Only invoices having issuing date (field "date") before the "until" date (included) will be listed*

### RELATED ENDPOINTS/REQUESTS
1. [Receipts](/docs/receipts/api)

## Read invoice
| HTTP METHOD &amp; HEADERS |
| ---- |
| **Endpoint URL** //invoice/invoice_id |
| **Accept**: application/*+json, application/json |
| **Content-Type**: application/json  |
| **Authorization**: Bearer Token  |

## Read **Sample** invoice
| HTTP METHOD &amp; HEADERS |
| ---- |
| **Endpoint URL** //invoice/sample |
| **Accept**: application/*+json, application/json |
| **Content-Type**: application/json  |
| **Authorization**: Bearer Token  |


# Examples
## List all endpoint
### List all (without query params)
#### Request (***GET*** http:///invoice)
``` [EMPTY REQUEST BODY]```

#### Response (**200 OK**)
```json
{
  "_links": {
    "self": {
      "href": "https:///invoice?page=1"
    },
    "first": {
      "href": "https:///invoice"
    },
    "last": {
      "href": "https:///invoice?page=10"
    },
    "next": {
      "href": "https:///invoice?page=2"
    }
  },
  "_embedded": {
    "invoices": [
      {
        "idinvoice": "98a78a9c-dd88-5da3-b188-cacd0c2b4414",
        "idinvoicer": "1",
        "invoicenumber": "101",
        "iduser": "4c3df859-d90f-5152-8a2e-31ee9a183998",
        "summary": {
          "total": 27,
          "saving": 1.14,
          "products": {
            "GOA": 25.23
          },
          "taxes": {
            "IVA_21": {
              "name": "IVA",
              "locale": "es_ES",
              "percent": "21",
              "amount": 4.59,
              "base": 22.31
            }
          }
        },
        "billingdata": {
          "billingName": "billingName",
          "billingAddress": "billingAddress",
          "billingId": "billingId",
          "billingWeb": null,
          "billingLogo": "billingLogo",
          "billingMail": null,
          "billingPhone": null
        },
        "created": "2015-11-26 17:28:33",
        "date": "2015-11-26",
        "_links": {
          "self": {
            "href": "https://your.server.com/invoice/98a78a9c-dd88-5da3-b188-cacd0c2b4414"
          }
        }
      },
      ...
    ]
  },
  "page_count": 10,
  "page_size": 10,
  "total_items": 100,
  "page": 1
}  
```

### List All Until Nov 25th 2015 (included)
#### Request (***GET*** http:///receipts?until=20151125)
``` [EMPTY REQUEST BODY]```

#### Response (**200 OK**)
```json
{
  "_links": {
    "self": {
      "href": "https:///receipts?until=20151125&page=1"
    },
    "first": {
      "href": "https:///receipts?until=20151125"
    },
    "last": {
      "href": "https:///receipts?until=20151125&page=16"
    },
    "next": {
      "href": "https:///receipts?until=20151125&page=2"
    }
  },
  "_embedded": {
    "invoices": [
      {
        "idinvoice": "0c7397c5-19f8-502c-934c-8d693ae5ca24",
        "idinvoicer": "1",
        "invoicenumber": "93",
        "iduser": "4c3df859-d90f-5152-8a2e-31ee9a183998",
        "summary": {
          "total": 27,
          "saving": 1.14,
          "products": {
            "GOA": 25.23
          },
          "taxes": {
            "IVA_21": {
              "name": "IVA",
              "locale": "es_ES",
              "percent": "21",
              "amount": 4.59,
              "base": 22.31
            }
          }
        },
        "billingdata": {
          "billingName": "billingName",
          "billingAddress": "billingAddress",
          "billingId": "billingId",
          "billingWeb": null,
          "billingLogo": "openy",
          "billingMail": null,
          "billingPhone": null
        },
        "created": "2015-11-20 20:26:24",
        "date": "2015-11-20",
        "_links": {
          "self": {
            "href": "https:///invoice/0c7397c5-19f8-502c-934c-8d693ae5ca24"
          }
        }
      },
      ...
    ]
  },
  "page_count": 10,
  "page_size": 10,
  "total_items": 92,
  "page": 1
}      
```


## Read invoice
### Read invoice (by invoice id)
#### Request (***GET*** http:///invoice/0c7397c5-19f8-502c-934c-8d693ae5ca24)
``` [EMPTY REQUEST BODY]```

#### Response (**200 OK**)
```json
{
  "idinvoice": "0c7397c5-19f8-502c-934c-8d693ae5ca24",
  "idinvoicer": "1",
  "invoicenumber": "93",
  "iduser": "4c3df859-d90f-5152-8a2e-31ee9a183998",
  "summary": {
    "total": 27,
    "saving": 1.14,
    "products": {
      "GOA": 25.23
    },
    "taxes": {
      "IVA_21": {
        "name": "IVA",
        "locale": "es_ES",
        "percent": "21",
        "amount": 4.59,
        "base": 22.31
      }
    }
  },
  "billingdata": {
    "billingName": "billingName",
    "billingAddress": "billingAddress",
    "billingId": "billingId",
    "billingWeb": null,
    "billingLogo": "openy",
    "billingMail": null,
    "billingPhone": null
  },
  "created": "2015-11-20 20:26:24",
  "date": "2015-11-20",
  "_embedded": {
    "receipts": [
      {
        "receiptid": "193",
        "idcompany": "1",
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
        "date": "2015-11-20 20:26:24",
        "idopystation": "1",
        "idinvoice": "0c7397c5-19f8-502c-934c-8d693ae5ca24",
        "idorder": "393",
        "_links": {
          "self": {
            "href": "https:///receipts/193"
          }
        }
      }
    ]
  },
  "_links": {
    "self": {
      "href": "https:///invoice/0c7397c5-19f8-502c-934c-8d693ae5ca24"
    }
  }
}
```

## Read **sample** invoice
#### Request (***GET*** http:///invoice/sample)
``` [EMPTY REQUEST BODY]```

#### Response (**200 OK**)
```json
{
  "idinvoice": "sample",
  "idinvoicer": "1",
  "invoicenumber": "89",
  "iduser": "sample",
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
        "amount": 0,
        "base": 0
      }
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
  "created": "2015-10-21 17:56:02",
  "date": "2015-11-20",
  "_links": {
    "self": {
      "href": "https:///invoice/sample"
    }
  },
  "_embedded": {
    "receipts": [
      {
        "idcompany": 2,
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
```


# **ERROR** Examples
## Invalid Bearer token
#### Request (***GET*** http:///invoice[?until=$date|/receipt_id])
#### Response (**401 Unauthorized**)

## Invalid "until" query parameter (when filtering until date)
#### Request (***GET*** http:///invoice?until=BAD_DATE
#### Response (**400 Bad request**)
```json
{
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Bad Request",
  "status": 400,
  "detail": "Invalid value for parameter \"until\""
}
```


## Non existing receipt id
### Read receipt (by receipt id)
#### Request (***GET*** http:///invoice/BAD_ID)
#### Response (**404 NOT FOUND**)
```json
{
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Not Found",
  "status": 404,
  "detail": "Invoice not found"
}
```

## Unauthorized receipt
### Read receipt (by receipt id)
#### Request (***GET*** http:///invoice/60)
#### Response (**404 NOT FOUND**)
```json
{
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Not Found",
  "status": 404,
  "detail": "Invoice not found"
}
```

==WHEN SESSION USER TRIES TO REACH AN EXISTING INVOICE FROM ANOTHER USER WE ANSWER A 404==
