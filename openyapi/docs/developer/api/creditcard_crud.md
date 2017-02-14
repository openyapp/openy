# Credit Card CRUD
[TOC]

## List Current User Credit Cards
| HTTP METHOD &amp; HEADERS |
| ---- |
| **GET** //creditcard |
| **Accept**: application/*+json, application/json |
| **Content-Type**: application/json  |
| **Authorization**: Bearer Token  |

## Fields
**NO FIELDS REQUIRED**

## RELATED ENDPOINTS/REQUESTS
1. [Credit Card Register](/docs/creditcard_register/api)
2. [Credit Cards GET Entity](#read-card-data)

# Examples
#### Request
``` [EMPTY REQUEST BODY]```

#### Response (**200 OK**)
```json
{
  "_links": {
    "self": {
      "href": "http:///creditcard?page=1"
    },
    "first": {
      "href": "http:///creditcard"
    },
    "last": {
      "href": "http:///creditcard?page=1"
    }
  },
  "_embedded": {
    "creditcard": [
      {
        "idcreditcard": "86e50f71-0c8d-5e03-8da1-43e186a3aea0",
        "cardusername": "sample user name",
        "pan": "0004",
        "validated": false,
        "expires": "12/17",
        "favorite": false,
        "active": false,
        "modified": "2015-09-01 14:43:19",
        "_links": {
          "self": {
            "href": "http:///creditcard/86e50f71-0c8d-5e03-8da1-43e186a3aea0"
          }
        }
      },
      {
        "idcreditcard": "23633541-1359-54ec-b150-172dac27dbb1",
        "cardusername": "sample user name",
        "pan": "0004",
        "validated": false,
        "expires": "12/17",
        "favorite": false,
        "active": false,
        "modified": "2015-09-01 14:37:53",
        "_links": {
          "self": {
            "href": "http:///creditcard/23633541-1359-54ec-b150-172dac27dbb1"
          }
        }
      },
      {
        "idcreditcard": "90531f38-3006-54ab-8b04-bc9f08692e4c",
        "cardusername": "new eads fucker",
        "pan": "0004",
        "validated": false,
        "expires": "12/17",
        "favorite": true,
        "active": false,
        "modified": "2015-09-01 13:59:51",
        "_links": {
          "self": {
            "href": "http:///creditcard/90531f38-3006-54ab-8b04-bc9f08692e4c"
          }
        }
      }
    ]
  },
  "page_count": 1,
  "page_size": 25,
  "total_items": 3,
  "page": 1
}
```


## Read Card Data
| HTTP METHOD &amp; HEADERS |
| ---- |
| **GET** //creditcard/86e50f71-0c8d-5e03-8da1-43e186a3aea0 |
| **Accept**: application/*+json, application/json |
| **Content-Type**: application/json  |
| **Authorization**: Bearer Token  |

## Fields
### URI_QUERY
* **idcreditcard** (Uuid) : Credit card identifier, obtained once registered ([see registry](/docs/creditcard_register/api) for learning how to register cards)

## RELATED ENDPOINTS/REQUESTS
1. [Credit Card Register](/docs/creditcard_register/api)
2. [Credit Cards GET Collection](#list-current-user-credit-cards)

# Examples
#### Request (**GET**)
``` [EMPTY REQUEST BODY]```

#### Response (**200 OK**)
```json
{
  "idcreditcard": "86e50f71-0c8d-5e03-8da1-43e186a3aea0",
  "cardusername": "sample user name",
  "pan": "0004",
  "validated": false,
  "expires": "12/17",
  "favorite": false,
  "active": false,
  "modified": "2015-09-01 14:43:19",
  "_links": {
    "self": {
      "href": "http:///creditcard/86e50f71-0c8d-5e03-8da1-43e186a3aea0"
    }
  }
}
```

## Change Credit Card Data
| HTTP METHOD &amp; HEADERS |
| ---- |
| **PATCH** //creditcard/86e50f71-0c8d-5e03-8da1-43e186a3aea0 |
| **Accept**: application/*+json, application/json |
| **Content-Type**: application/json  |
| **Authorization**: Bearer Token  |

## Fields
### URI_QUERY
* **idcreditcard** (Uuid) : Credit card identifier, obtained once registered ([see registry](/docs/creditcard_register/api) for learning how to register cards)
### BODY
* **cardusername** (String) : *User name used for identifying a credit card*

# Examples
#### Request (**PATCH**)
```json
{
  "cardusername" : "new card name"
}
```

#### Response (**200 OK**)
```json
{
  "idcreditcard": "86e50f71-0c8d-5e03-8da1-43e186a3aea0",
  "cardusername": "new card name",
  "pan": "0004",
  "validated": false,
  "expires": "12/17",
  "favorite": false,
  "active": false,
  "modified": "2015-09-01 18:20:54",
  "_links": {
    "self": {
      "href": "http:///creditcard/86e50f71-0c8d-5e03-8da1-43e186a3aea0"
    }
  }
}
```
## **Error** Examples
### No Card Found for partial update
#### Response (**404 Not Found**)
```json
{
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Not Found",
  "status": 404,
  "detail": "Card Not found"
}
```

## Delete Credit Card Data
| HTTP METHOD &amp; HEADERS |
| ---- |
| **DELETE** //creditcard/86e50f71-0c8d-5e03-8da1-43e186a3aea0 |
| **Accept**: application/*+json, application/json |
| **Content-Type**: application/json  |
| **Authorization**: Bearer Token  |

## Fields
### URI_QUERY
* **idcreditcard** (Uuid) : Credit card identifier, obtained once registered ([see registry](/docs/creditcard_register/api) for learning how to register cards)

# Examples
#### Request (**DELETE**)
``` [EMPTY REQUEST BODY]```

#### Response (**204 No Content**)
``` [EMPTY RESPONSE BODY]```

## **Error** Examples
### No Card Found for deletion
#### Response (**422 Unprocessable Entity**)
```json
{
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Unprocessable Entity",
  "status": 422,
  "detail": "Unable to delete entity."
}
```