# Receipts

## List all (**paginated**, **filtered** by session user and **ordered** desc by date)
| HTTP METHOD &amp; HEADERS |
| ---- |
| **Endpoint URL** //receipts |
| **Accept**: application/*+json, application/json |
| **Content-Type**: application/json  |
| **Authorization**: Bearer Token  |

### Query parameters
(all of them are optional)

* page (Unsigned Int) : *Assumed "1" by default*
* until (8 digits date in format "YYYYMMDD") : *Top date for receipts listed. Only receipts issued before date (included) will be listed*

## List all by given order (**paginated**, **filtered** by session user and **filtered** by **order id**)
| HTTP METHOD &amp; HEADERS |
| ---- |
| **Endpoint URL** //receipts |
| **Accept**: application/*+json, application/json |
| **Content-Type**: application/json  |
| **Authorization**: Bearer Token  |

### Query parameter
(required)

* **order** (Unsigned Int) : *Order id what was billed*

### RELATED ENDPOINTS/REQUESTS
1. [Orders](/docs/orders/api)

## Read receipt
| HTTP METHOD &amp; HEADERS |
| ---- |
| **Endpoint URL** //receipts/receipt_id |
| **Accept**: application/*+json, application/json |
| **Content-Type**: application/json  |
| **Authorization**: Bearer Token  |



# Examples
## List all endpoint
### List all (without query params)
#### Request (***GET*** http:///receipts)
``` [EMPTY REQUEST BODY]```

#### Response (**200 OK**)
```json
{
  "_links": {
    "self": {
      "href": "http:///receipts?page=1"
    },
    "first": {
      "href": "http:///receipts"
    },
    "last": {
      "href": "http:///receipts?page=3"
    },
    "next": {
      "href": "http:///receipts?page=2"
    }
  },
  "_embedded": {
    "receipts": [
      {
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
      },
      ...
   ]
  },
  "page_count": 3,
  "page_size": 25,
  "total_items": 60,
  "page": 1
}
```

## List all endpoint
### List All Until Sept 8th 2015 (included)
#### Request (***GET*** http:///receipts?until=20150908)
``` [EMPTY REQUEST BODY]```

#### Response (**200 OK**)
```json
{
  "_links": {
    "self": {
      "href": "http:///receipts?until=20150908&page=1"
    },
    "first": {
      "href": "http:///receipts?until=20150908"
    },
    "last": {
      "href": "http:///receipts?until=20150908&page=3"
    },
    "next": {
      "href": "http:///receipts?until=20150908&page=2"
    }
  },
  "_embedded": {
    "receipts": [
      {
        "receiptid": "59",
        "summary": {
          "data": "SERIALIZED DATA",
          "details": {
            "Fecha": "08/09/2015 11:17",
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
        "date": "2015-09-08 11:17:20",
        "_links": {
          "self": {
            "href": "http:///receipts/59"
          }
        }
      },
      ...
    ]
  },
  "page_count": 3,
  "page_size": 25,
  "total_items": 57,
  "page": 1
}
```

## List all by given order
### List all (filtered by order id)
#### Request (***GET*** http:///receipts?idorder=2948)
``` [EMPTY REQUEST BODY]```

#### Response (**200 OK**)
```json
{
  "_links": {
    "self": {
      "href": "http:///receipts?idorder=2948&page=1"
    },
    "first": {
      "href": "http:///receipts?idorder=2948"
    },
    "last": {
      "href": "http:///receipts?idorder=2948&page=1"
    }
  },
  "_embedded": {
    "receipts": [
      {
        "receiptid": "61",
        "summary": {
          "data": "SERIALIZED DATA",
          "details": {
            "Fecha": "30/10/2015 11:06",
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
        "date": "2015-10-30 11:06:15",
        "_links": {
          "self": {
            "href": "http:///receipts/61"
          }
        }
      }
    ]
  },
  "page_count": 1,
  "page_size": 25,
  "total_items": 1,
  "page": 1
}
```

## Read receipt
### Read receipt (by receipt id)
#### Request (***GET*** http:///receipts/63)
``` [EMPTY REQUEST BODY]```

#### Response (**200 OK**)
```json
{
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
```


# **ERROR** Examples
## Invalid Bearer token
### List all (without query params)
#### Request (***GET*** http:///receipts)
#### Response (**401 Unauthorized**)

## Non existing order id
### List all (filtered by order id)
#### Request (***GET*** http:///receipts?idorder=BAD_ID)
#### Response (**200 OK**)
```json
{
  "_links": {
    "self": {
      "href": "http:///receipts?idorder=BAD_ID"
    },
  },
  "_embedded": {
    "receipts": []
  },
  "page_count": 0,
  "page_size": 25,
  "total_items": 0,
  "page": 0
}
```

## Non existing receipt id
### Read receipt (by receipt id)
#### Request (***GET*** http:///receipts/BAD_ID)
#### Response (**404 NOT FOUND**)
```json
{
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Not Found",
  "status": 404,
  "detail": "Receipt not found"
}
```

## Unauthorized receipt
### Read receipt (by receipt id)
#### Request (***GET*** http:///receipts/60)
#### Response (**404 NOT FOUND**)
```json
{
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Not Found",
  "status": 404,
  "detail": "Receipt not found"
}
```

==WHEN SESSION USER TRIES TO REACH AN EXISTING RECEIPT FROM ANOTHER USERS WE ANSWER A 404==
