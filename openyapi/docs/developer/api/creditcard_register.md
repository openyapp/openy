# Credit Card Register



| HTTP METHOD & HEADERS |
| ---- |
| **`POST`** `//creditcard` |
| **Accept**: application/*+json, application/json |
| **Content-Type**: application/json  |
| **Authorization**: Bearer Token  |

## Fields
(required **in bold**)

* **pan** (16 digits Unsigned Int) : *Must be a valid credit card number*
* **year** (2 digits Unsigned Int) : *Expiry Year in 2 digits*
* **month** (2 digits Unsigned Int) : *Expiry Month*
* **cvv** (3 digits Unsigned Int) : *Verification Code*
* **cardusername** (String) : *User name used for identifying a credit card*

## RELATED ENDPOINTS/REQUESTS
1. [Credit Card Validation](#credit-card-validation)
2. [Credit Cards CRUD](/docs/creditcard_crud/api)


# Examples
#### Request
```json
{
	"pan" : "panpan",
    "year" : 17,
    "month" : 12,
    "cardusername" : "sample user name",
    "cvv" : "123"
}
```
#### Response (**201 Created**)
```json
{
  "idcreditcard": "2ced69aa-f641-530e-89ce-a49c457305ff",
  "cardusername": "sample user name",
  "pan": "0004",
  "validated": false,
  "expires": "12/17",
  "favorite": false,
  "active": false,
  "modified": "2015-09-01 14:05:58",
  "_links": {
    "self": {
      "href": "http:///creditcard/2ced69aa-f641-530e-89ce-a49c457305ff"
    }
  }
}
```

# **Error** Examples
## INPUT FORMAT ERRORS
### Missing Values
#### Request
```json
{
}
```
#### Response (**422 Unprocessable Entity**)
```json
{
  "validation_messages": {
    "cardusername": {
      "isEmpty": "Value is required and can't be empty"
    },
    "year": {
      "isEmpty": "Value is required and can't be empty"
    },
    "month": {
      "isEmpty": "Value is required and can't be empty"
    },
    "pan": {
      "isEmpty": "Value is required and can't be empty"
    },
    "cvv": {
      "isEmpty": "Value is required and can't be empty"
    }
  },
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Unprocessable Entity",
  "status": 422,
  "detail": "Failed Validation"
}
```


### Bad values
#### Request
```json
{
	"pan" : "JJJJ812049400004",
    "year" : "a",
    "month" : 13,
    "cardusername" : "sample user name",
    "cvv" : "1"
}
```
#### Response (**422 Unprocessable Entity**)
```json
{
  "validation_messages": {
    "year": {
      "notInt": "Year field must be an integer",
      "notBetween": "Year field must be an integer between 0 and 99",
    },
    "month": {
      "notBetween": "Month field must be an integer between 1 and 12"
    },
    "pan": {
      "creditcardContent": "Not a valid credit card"
    },
    "cvv": {
      "stringLengthTooShort": "The input is less than 3 characters long"
    }
  },
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Unprocessable Entity",
  "status": 422,
  "detail": "Failed Validation"
}
```

## INTERNAL ERRORS
### Error response from Bank POS Service
#### Request
```json
{
	"pan" : "panpan",
    "year" : 17,
    "month" : 12,
    "cardusername" : "sample user name",
    "cvv" : "123"
}
```
#### Response (**500 Internal Server Error**)
```json
{
  "additional details": {
    "text": "Error en el cálculo del algoritmo HASH",
    "code": "SIS0042",
    "translations": {
      "es_ES": "Error en comunicación con TPV (SIS0042)"
    }
  },
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Internal Server Error",
  "status": 500,
  "detail": "Error en comunicación con TPV (SIS0042)"
}
```

### Connection not stablished with Bank POS Service or other Unknown Error
#### Request
```json
{
	"pan" : "panpan",
    "year" : 17,
    "month" : 12,
    "cardusername" : "sample user name",
    "cvv" : "123"
}
```
#### Response (**500 Internal Server Error**)
```json
{
  "additional details": {
    "text": "Estado desconocido (\"HTTP 520 Unknown Error\")",
    "code": "520",
    "translations": []
  },
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Internal Server Error",
  "status": 500,
  "detail": "Estado desconocido (\"HTTP 520 Unknown Error\")"
}
```

* * *
* * *

# Credit Card Validation

| HTTP METHOD & HEADERS |
| ---- |
| **`POST`** `//creditcardvalidation` |
| **Accept**: application/*+json, application/json |
| **Content-Type**: application/json  |
| **Authorization**: Bearer Token  |

## Fields
(required **in bold**)

* **idcreditcard** (UUID) : *Must be a valid (existing) credit card UUID for session user*
* **amount** (2 decimal digits FLOAT NUMBER) : "Must be a valid float number (using "." point decimal separator). Its number is retrieved from bank account

## RELATED ENDPOINTS/REQUESTS
1. [Credit Card Register](#credit-card-register)

# Examples
#### Request
```json
{
	"idcreditcard" : "40f0227e-556e-5c3c-824d-58803bcfd9c1",
    "amount" : 0.09
}
```

#### Response (**200 OK**)
```json
{
  "idcreditcard": "40f0227e-556e-5c3c-824d-58803bcfd9c1",
  "cardusername": "sample user name",
  "pan": "0004",
  "validated": true,
  "expires": "12/17",
  "favorite": false,
  "active": true,
  "modified": "2015-09-01 12:46:18"
}
```

# **Error** Examples
## INPUT FORMAT ERROR
### Card identifier
#### Request
```json
{
	"idcreditcard":"BAD UUID",
	"amount":0.09
}
```
#### Response (**422 Unprocessable Entity**)
```json
{
  "validation_messages": {
    "idcreditcard": {
      "stringLengthTooShort": "The input is less than 36 characters long"
    }
  },
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Unprocessable Entity",
  "status": 422,
  "detail": "Failed Validation"
}
```

## VALIDATION ERRORS
### Wrong Amount
#### Request
```json
{
	"idcreditcard":"40f0227e-556e-5c3c-824d-58803bcfd9c1",
	"amount":9999
}
```
#### Response (**401 Unauthorized** )
```json
{
  "additional details": {
    "attempts remaining": 2
  },
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Unauthorized",
  "status": 401,
  "detail": "Wrong amount"
}
```

### Attempts limit reached
#### Request
```json
{
	"idcreditcard":"40f0227e-556e-5c3c-824d-58803bcfd9c1",
	"amount":9999
}
```

#### Response (**403 Forbidden** )
```json
{
  "additional details": {
    "idcreditcard": "40f0227e-556e-5c3c-824d-58803bcfd9c1",
    "modified": null
  },
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Forbidden",
  "status": 403,
  "detail": "Attempts limit reached (3 attempts)"
}
```

### Card Does not exist
#### Request
```json
{
	"idcreditcard" : "40f0227e-556e-5c3c-824d-58803bcfd9c1",
    "amount" : 0.09
}
```

#### Response (**404 Not Found** )
```json
{
  "additional details": {
    "idcreditcard": "40f0227e-556e-5c3c-824d-58803bcfd9c1",
    "modified": null
  },
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Not Found",
  "status": 404,
  "detail": "Card not found"
}
```


### Card Already Validated
#### Request
```json
{
	"idcreditcard" : "40f0227e-556e-5c3c-824d-58803bcfd9c1",
    "amount" : 0.09
}
```

#### Response (**410 Gone** )
```json
{
  "additional details": {
    "idcreditcard": "40f0227e-556e-5c3c-824d-58803bcfd9c1",
    "modified": null
  },
  "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html",
  "title": "Gone",
  "status": 410,
  "detail": "Card already validated"
}
```
