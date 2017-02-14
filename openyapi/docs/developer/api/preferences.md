#Get User Preferences

`GET //preference/f7914e2b-b903-57cb-9b37-2063ea0ed1c8`   
Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer 79222c5976553dd03061b635db327a73d71e634c  
	
####Response
	{
	    "iduser": "b1b9e2d1-0a0d-56c6-b0eb-17913a41f514",
	    "payment_pin": "123123",
	    "default_credit_card": "12",
	    "inv_name": "test",
	    "inv_country": "Spain",
	    "inv_address": "Spme address",
	    "inv_locality": "Barcelona",
	    "inv_postal_code": "08005",
	    "inv_document_type": "DNI",
	    "inv_document": "43678290p",
	    "inv_cicle": "inmediato",
	    "_links": {
	        "self": {
	            "href": "http:///preference/b1b9e2d1-0a0d-56c6-b0eb-17913a41f514"
	        }
	    }
	}
	
#Change User Preferences

 - Sent parameters to change
 - To delete sent parameter to __null__ 
 
`PATCH //preference/f7914e2b-b903-57cb-9b37-2063ea0ed1c8`   
Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer 79222c5976553dd03061b635db327a73d71e634c  

####Request
	{
	    "default_credit_card": "e69ff170-d487-54b2-aa6e-1b7a9527b559"
	}
	
####Response
	{
	    "iduser": "b1b9e2d1-0a0d-56c6-b0eb-17913a41f514",
	    "payment_pin": "123123",
	    "default_credit_card": "e69ff170-d487-54b2-aa6e-1b7a9527b559",
	    "inv_name": "test",
	    "inv_country": "Spain",
	    "inv_address": "Spme address",
	    "inv_locality": "Barcelona",
	    "inv_postal_code": "08005",
	    "inv_document_type": "DNI",
	    "inv_document": "43678290p",
	    "inv_cicle": "inmediato",
	    "_links": {
	        "self": {
	            "href": "http:///preference/b1b9e2d1-0a0d-56c6-b0eb-17913a41f514"
	        }
	    }
	}
	
#Change preference: _Set user pin_ 

 - Example of change user preference _payment_pin_ 
 
`PATCH //preference/f7914e2b-b903-57cb-9b37-2063ea0ed1c8`   
Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer 79222c5976553dd03061b635db327a73d71e634c

####Request
	{
	    "payment_pin": 123123
	}
	
####Response
	{
	    "iduser": "b1b9e2d1-0a0d-56c6-b0eb-17913a41f514",
	    "payment_pin": "123123",
	    "default_credit_card": null,
	    "inv_name": "test",
	    "inv_country": "Spain",
	    "inv_address": "Spme address",
	    "inv_locality": "Barcelona",
	    "inv_postal_code": "08005",
	    "inv_document_type": "DNI",
	    "inv_document": "43678290p",
	    "inv_cicle": "inmediato",
	    "_links": {
	        "self": {
	            "href": "http:///preference/b1b9e2d1-0a0d-56c6-b0eb-17913a41f514"
	        }
	    }
	}
	
#Change preference: _Unset user pin_ 

 - Example of change user preference _payment_pin_ 
 
`PATCH //preference/f7914e2b-b903-57cb-9b37-2063ea0ed1c8`   
Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer 79222c5976553dd03061b635db327a73d71e634c

####Request
	{
	    "payment_pin": null
	}
	
####Response
	{
	    "iduser": "b1b9e2d1-0a0d-56c6-b0eb-17913a41f514",
	    "payment_pin": null,
	    "default_credit_card": null,
	    "inv_name": "test",
	    "inv_country": "Spain",
	    "inv_address": "Spme address",
	    "inv_locality": "Barcelona",
	    "inv_postal_code": "08005",
	    "inv_document_type": "DNI",
	    "inv_document": "43678290p",
	    "inv_cicle": "inmediato",
	    "_links": {
	        "self": {
	            "href": "http:///preference/b1b9e2d1-0a0d-56c6-b0eb-17913a41f514"
	        }
	    }
	}
	
#Change preference: _Invoice data_ 

 - Example of change user preference _payment_pin_ 
 
`PATCH //preference/f7914e2b-b903-57cb-9b37-2063ea0ed1c8`   
Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer 79222c5976553dd03061b635db327a73d71e634c

####Request
	{
		"inv_name": "test",
	    "inv_country": "Spain",
	    "inv_address": "Spme address",
	    "inv_locality": "Barcelona",
	    "inv_postal_code": "08005",
	    "inv_document_type": "DNI",
	    "inv_document": "43678290p",
	    "inv_cicle": "inmediato"
	}
	
####Response
	{
	    "iduser": "b1b9e2d1-0a0d-56c6-b0eb-17913a41f514",
	    "payment_pin": "123123",
	    "default_credit_card": null,
	    "inv_name": "test",
	    "inv_country": "Spain",
	    "inv_address": "Spme address",
	    "inv_locality": "Barcelona",
	    "inv_postal_code": "08005",
	    "inv_document_type": "DNI",
	    "inv_document": "43678290p",
	    "inv_cicle": "inmediato",
	    "_links": {
	        "self": {
	            "href": "http:///preference/b1b9e2d1-0a0d-56c6-b0eb-17913a41f514"
	        }
	    }
	}