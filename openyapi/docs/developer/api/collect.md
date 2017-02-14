#Collect

## Order status

	STATUS_NOT_EXISTING = 0; 	// Order does not exist
	STATUS_CANCELLED = 1; 		// Cancelled by user
	STATUS_ORDERED = 2; 		// Order is registered in Openy
	STATUS_AUTHORIZED = 3; 		// Authorized by Openy to be delivered/served
									// e.g. User have enough bank account balance
									// e.g.2 User have enough Openy vouchers
	STATUS_ISSUED = 4; 			// Order has been issued for delivery
	STATUS_DELIVERED = 4; 		// Order has been delivered
	STATUS_PAYED = 5;
	STATUS_INVOICED = 6;
 
## Collect Order

 - Register an empty order to current user

`POST //collect`
 
Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984
	
####Request
	{
	    "idoffstation": "1",
	  	"pump":"3", 
	    "fueltype": "G95",
	  	"idorder":"88"
	}
	
####Response
	{
	    "receiptid": "4",
	    "receiptposid": "f80958a1-c037-530d-a95a-33f0e7079c08",
	    "summary": {
	        "data": "O:8:\"stdClass\":5:{s:12:\"idopystation\";s:1:\"1\";s:4:\"pump\";s:1:\"3\";s:8:\"fueltype\";s:3:\"G95\";s:7:\"idorder\";s:2:\"88\";s:4:\"date\";s:21:\"20//09//2015 17:29:22\";}",
	        "details": {
	            "Fecha": "2015-09-20 17:29:22",
	            "Precio/lt": 1.099,
	            "Litros": 26.572187776794,
	            "Precio": 1.099,
	            "IVA": 3.15,
	            "Total": 15,
	            "Ahorro": 15
	        }
	    },
	    "taxes": {
	        "1": {
	            "name": "IVA",
	            "locale": "es_ES",
	            "percent": "21"
	        }
	    },
	    "amount": "15",
	    "billingdata": {
	        "billingName": "Openy Fake Station",
	        "billingAddress": "Av. Icaria 08000 Barcelona espiña",
	        "billingId": "00000000-T",
	        "billingWeb": null,
	        "billingLogo": "meroil",
	        "billingMail": "mail@openy.es",
	        "billingPhone": null
	    },
	    "template": null,
	    "date": "2015-09-20 17:29:23",
	    "idpayment": "b6f2e4ea-3c92-5ae1-9b9e-2f1312ad4aa3",
	    "idopystation": "1",
	    "_links": {
	        "self": {
	            "href": "http:///collect"
	        }
	    }
	}

## RE Request after first request
	{
	    "idoffstation": "1",
	    "pump":"3", 
	    "fueltype": "G95",
	    "idorder":"42"
	}
	
#### Response: (202) No Content 
	{
	    "collect": {
	        "id_sell": "5906",
	        "date": "2015-10-24 09:58:38",
	        "price_per_unit": "1.084000000",
	        "units": "18.45000",
	        "price": "1.084000000",
	        "iva": null,
	        "total": "20.00",
	        "discount": 0
	    },
	    "order": {
	        "receiptid": "6",
	        "receiptposid": "4996926c-c6ca-590c-b80e-12ec7d334fb2",
	        "summary": {
	            "data": "O:8:\"stdClass\":6:{s:12:\"idoffstation\";s:1:\"1\";s:4:\"pump\";s:1:\"3\";s:8:\"fueltype\";s:3:\"G95\";s:7:\"idorder\";s:2:\"42\";s:12:\"idopystation\";s:1:\"1\";s:4:\"date\";s:21:\"24//10//2015 10:33:35\";}",
	            "details": {
	                "Fecha": "2015-10-24 09:58:19",
	                "Precio/lt": "1.084000000",
	                "Litros": "18.45000",
	                "Precio": "1.084000000",
	                "IVA": 4.2,
	                "Total": "20.00",
	                "Ahorro": 0
	            }
	        },
	        "taxes": {
	            "1": {
	                "name": "IVA",
	                "locale": "es_ES",
	                "percent": "21"
	            }
	        },
	        "amount": "20",
	        "billingdata": {
	            "billingName": "Openy Fake Station",
	            "billingAddress": "Av. Icaria 08000 Barcelona espiña",
	            "billingId": "00000000-T",
	            "billingWeb": null,
	            "billingLogo": "meroil",
	            "billingMail": "mail@openy.es",
	            "billingPhone": null
	        },
	        "template": null,
	        "date": "2015-10-24 10:33:35",
	        "idpayment": "b7f8ad25-65db-57ff-a814-f4b9c1d4a407",
	        "idopystation": "1"
	    },
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-202",
	    "title": "No Content",
	    "status": 202,
	    "detail": "This Order is already paid"
	}
	
	