#Refuel

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
 
## Register Order

 - Register an empty order

`POST //refuel`

 - idoffstation required
 - pump required
 - fueltype required
 - amount required
 - email required
 - antifraudPin optional
 - userPin optional (as set in preference)
  
 
Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984
	
####Request
	{
	    "idoffstation": "1",
	  	"pump":"3", 
	    "fueltype": "G95",
	    "amount": "30",
	  	"price":"1.099",
		"email":"@gmail.com",
	  	"antifraudPin":"5310",
	  	"userPin":"1234"
	}

### On Response

Two Asynchronous process start 

  - MonitorRaisePump: this will PUSH TO APP -> now by email 
  - MonitorHangPump: this will PUSH TO APP -> now by email

 
####Response: the refuel has been set correctly
	{
	    "allowRaisePump": true,
	    "refuel": {
	        "idoffstation": "1",
	        "pump": "3",
	        "fueltype": "G95",
	        "amount": "30",
	        "price": "1.099",
	        "email": "@gmail.com",
	        "antifraudPin": "5310",
	        "userPin": "1234",
	        "toRefuel": 45,
	        "idopystation": "1"
	    },
	    "security": {
	        "chain": {
	            "userPin": {
	                "satisfied": true,
	                "remaining_attempts": 4
	            }
	        },
	        "verification": true
	    },
	    "_embedded": {
	        "order": {
	            "idorder": "37",
	            "idopystation": "1",
	            "summary": null,
	            "amount": "30",
	            "iduser": "f7914e2b-b903-57cb-9b37-2063ea0ed1c8",
	            "idpayment": null,
	            "paymentmethod": "1",
	            "deliverycode": null,
	            "orderstatus": {
	                "status": 3,
	                "idorder": "37",
	                "paymentoperationid": null,
	                "lastresponse": null,
	                "lastcode": null,
	                "codemsg": null,
	                "openymsg": null
	            },
	            "created": "2015-10-21 17:56:02",
	            "updated": "2015-10-21 17:56:03",
	            "_links": {
	                "self": {
	                    "href": "http:///orders/37"
	                }
	            }
	        },
	        "price": {
	            "idPromotion": "14",
	            "units": 26.572187776794,
	            "originalPricePerUnit": "1.129000000",
	            "value": 30,
	            "discountPerUnit": 0.03,
	            "discountPercentage": 0.5,
	            "pricePerUnit": 1.099,
	            "promPricePorcentage": 15,
	            "promPricePerIUnit": 29.202834366696,
	            "promoUnits": 27.29754322111,
	            "promType": "discount",
	            "discount": 15,
	            "priceToPay": 15,
	            "_links": []
	        },
	        "amount": {
	            "code": 200,
	            "response": {
	                "result": "OK",
	                "request": {
	                    "command": "004",
	                    "pump": "3",
	                    "product": 5,
	                    "price": "1.099",
	                    "amount": 45
	                }
	            },
	            "_links": []
	        }
	    },
	    "_links": {
	        "self": {
	            "href": "http:///refuel"
	        }
	    }
	}

## Without antifraudPin & userPin
####Request: 
	{
	 "idoffstation":"1",
	 "pump":"3",
	 "fueltype":"G95",
	 "amount":"20",
	 "price":"1.269000000",
	 "email":"info@webtop.es",
	 "antifraudPin":"",
	 "userPin":""
	}
	
####Response:
	{
	    "details": [
	        {
	            "chain": {
	                "userPin": {
	                    "satisfied": false,
	                    "remaining_attempts": "unknown"
	                }
	            },
	            "verification": false
	        }
	    ],
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-307",
	    "title": "Temporary redirect",
	    "status": 307,
	    "detail": "Security Chain Required"
	}


##With userPin wrong.
 
 - API report the remaining number of attempts
 - If remaining attempts = 0, model with order cancelled  

####Request: 

	{
	 "idoffstation":"1",
	 "pump":"3",
	 "fueltype":"G95",
	 "amount":"20",
	 "price":"1.269000000",
	 "email":"info@webtop.es",
	 "antifraudPin":"",
	 "userPin":"1111"
	}
		
####Response:
	{
	    "details": [
	        {
	            "chain": {
	                "userPin": {
	                    "satisfied": false,
	                    "remaining_attempts": 3
	                }
	            },
	            "verification": false
	        }
	    ],
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-307",
	    "title": "Temporary redirect",
	    "status": 307,
	    "detail": "Security Chain Required"
	}
	
##Error on payment
 
 - The payment method is credtCard
 - The creditCard is current creditCard set in user preferences

####Response:
	{
	    "details": {
	        "errorCode": "0",
	        "errorMessage": null
	    },
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-402",
	    "title": "Payment Required",
	    "status": 402,
	    "detail": "Bank error"
	}	
	
# Refuel Log Trace

Complete refuel process

	2015-10-27T13:24:16+01:00 INFO (6): --------- Refuel BEGIN ---------
	2015-10-27T13:24:16+01:00 DEBUG (7): Security Chain Required: TRUE
	2015-10-27T13:24:19+01:00 DEBUG (7): Order Registered: 263
	2015-10-27T13:24:20+01:00 DEBUG (7): Order Authorized: {"status":3,"idorder":"263","paymentoperationid":null,"lastresponse":null,"lastcode":null,"codemsg":null,"openymsg":null}
	2015-10-27T13:24:23+01:00 DEBUG (7): To refuel: 20.176366843034
	2015-10-27T13:24:23+01:00 DEBUG (7): Send: {"command":"PRESET_AMOUNT","pump":"3","product":5,"price":"1.099","amount":20.176366843034}
	2015-10-27T13:24:24+01:00 DEBUG (7): To amount: {"code":200,"response":{"result":"OK","request":{"command":"004","pump":"3","product":5,"price":"1.099","amount":20.176366843034}}}
	2015-10-27T13:24:24+01:00 DEBUG (7): Curl Call Raise: "curl -i -H \"Accept: application\/json\" -H \"Content-Type: application\/json\" -H \"Authorization: Bearer 7476ec5852641fcda3f64bccf5fdd2217018cbe3\" http:\/\/\/opystation\/monitorraisepump\/3\/3\/G95\/263 > \/dev\/null 2>\/dev\/null &"
	2015-10-27T13:24:32+01:00 DEBUG (7): "aadapter monitorRaisePump start"
	2015-10-27T13:24:40+01:00 DEBUG (7): pump status: {"timestamp":"2015-10-27 13:24:40","pumpRaised":"true","status":{"No de Computador (2)":"03","Codigo producto (2)":"05","Numero Suministro (6)":"000010","Importe A2 (8)":"00110416","Litros A2 (6)":"000978","Estado Surtidor (2)":"89"}}
	2015-10-27T13:24:40+01:00 DEBUG (7): {"registration_ids":["APA91bHnw9krIKPc0bXUzL1-O3jx0TZ-SvX15IfFi8ir_yxrLSDe1_kXKB-m8no8G_lwFAYNwWvsekyT9cMAC-pwM9jlUlssue1Ko4f1HDvBH6a9cFS3SFOsUf1YAeDLDXnAuMjsuEia"],"data":{"data":{"idorder":"263","idopystation":"3","pump":"3","pumpRaised":"true","timestamp":"2015-10-27 13:24:40"},"title":"Has iniciado un repostaje con Openy!","message":"Orden: 263","vibrate":1,"sound":1}}
	2015-10-27T13:24:41+01:00 DEBUG (7): Curl Call Hang: 263: "curl -i -H \"Accept: application\/json\" -H \"Content-Type: application\/json\" -H \"Authorization: Bearer 7476ec5852641fcda3f64bccf5fdd2217018cbe3\" http:\/\/\/opystation\/monitorhangpump\/3\/3\/G95\/263 > \/dev\/null 2>\/dev\/null &"
	2015-10-27T13:24:41+01:00 DEBUG (7): done TRUE raise: 263: {"timestamp":"2015-10-27 13:24:40","pumpRaised":"true","status":{"No de Computador (2)":"03","Codigo producto (2)":"05","Numero Suministro (6)":"000010","Importe A2 (8)":"00110416","Litros A2 (6)":"000978","Estado Surtidor (2)":"89"}}
	2015-10-27T13:24:47+01:00 DEBUG (7): "aadapter monitorHangPump start"
	2015-10-27T13:24:56+01:00 DEBUG (7): estado: "8A"
	2015-10-27T13:24:56+01:00 DEBUG (7): Send: {"command":"BLOCK_PUMP","pump":"3"}
	2015-10-27T13:24:57+01:00 DEBUG (7): finish blockPump: {"code":200,"response":{"result":"OK","request":{"command":"002","pump":"3"}}}
	2015-10-27T13:24:57+01:00 DEBUG (7): Send: {"command":"COLLECT_SUPPLY","pump":"3"}
	2015-10-27T13:24:59+01:00 DEBUG (7): finish collectSupply: {"code":200,"response":{"result":"OK","request":{"command":"007","pump":"3"}}}
	2015-10-27T13:24:59+01:00 DEBUG (7): finish RefuelService: {"finish":true,"blockPump":{"code":200,"response":{"result":"OK","request":{"command":"002","pump":"3"}}},"collectSupply":{"code":200,"response":{"result":"OK","request":{"command":"007","pump":"3"}}}}
	2015-10-27T13:24:59+01:00 DEBUG (7): monitor refuel: {"pumpStatus":{"timestamp":"2015-10-27 13:24:56","pumpHanged":"true","status":{"No de Computador (2)":"03","Codigo producto (2)":"05","Numero Suministro (6)":"000011","Importe A2 (8)":"00110416","Litros A2 (6)":"000978","Estado Surtidor (2)":"8A"}},"finish":{"finish":true,"blockPump":{"code":200,"response":{"result":"OK","request":{"command":"002","pump":"3"}}},"collectSupply":{"code":200,"response":{"result":"OK","request":{"command":"007","pump":"3"}}}}}
	2015-10-27T13:24:59+01:00 DEBUG (7): pumpStatus: {"pumpStatus":{"timestamp":"2015-10-27 13:24:56","pumpHanged":"true","status":{"No de Computador (2)":"03","Codigo producto (2)":"05","Numero Suministro (6)":"000011","Importe A2 (8)":"00110416","Litros A2 (6)":"000978","Estado Surtidor (2)":"8A"}},"finish":{"finish":true,"blockPump":{"code":200,"response":{"result":"OK","request":{"command":"002","pump":"3"}}},"collectSupply":{"code":200,"response":{"result":"OK","request":{"command":"007","pump":"3"}}}}}
	2015-10-27T13:24:59+01:00 DEBUG (7): data: {"idoffstation":"9085","pump":"3","fueltype":"G95","idorder":"263"}
	2015-10-27T13:24:59+01:00 DEBUG (7): start collect insert: {"idoffstation":"9085","pump":"3","fueltype":"G95","idorder":"263"}
	2015-10-27T13:24:59+01:00 DEBUG (7): userData: {"username":"devel@gmail.com","password":"$2y$14$2QbEyMCS5PYVNgJX5arqluEoyrynLpdDOsbKXnyT7tgdP3tspySAy","first_name":"Agustin","last_name":"Calderon","created":"2015-10-19 01:16:23","phone_number":"3412341234","iduser":"1f877fa9-1c73-56fb-bcf0-373a25b4e66a","token":"N2I0ZjA5NTdjMGZmZjJhZjllMjUzOTg1MTlmN2RhNjI=","code_user":"12","role":null}
	2015-10-27T13:25:03+01:00 DEBUG (7): collect: {"id_sell":"169875","date":"2015-10-27 13:25:02","price_per_unit":1.124,"units":17.795414462081,"price":1.124,"iva":null,"total":20.002045855379,"discount":0.17795414462081}
	2015-10-27T13:25:03+01:00 DEBUG (7): order: {"idorder":"263","idopystation":"3","summary":null,"amount":"20","iduser":"1f877fa9-1c73-56fb-bcf0-373a25b4e66a","idpayment":null,"paymentmethod":"1","deliverycode":null,"paymentmethodid":"01f4ad33-39a8-5d0b-8f7f-f048bacb797e","orderstatus":"3","created":"2015-10-27 13:24:19","updated":"2015-10-27 13:24:20"}
	2015-10-27T13:25:03+01:00 DEBUG (7): order ref status : 5
	2015-10-27T13:25:03+01:00 DEBUG (7): order status : 3
	2015-10-27T13:25:03+01:00 DEBUG (7): to collect details: {"summary":{"data":"O:8:\"stdClass\":6:{s:12:\"idoffstation\";s:4:\"9085\";s:4:\"pump\";s:1:\"3\";s:8:\"fueltype\";s:3:\"G95\";s:7:\"idorder\";s:3:\"263\";s:12:\"idopystation\";s:1:\"3\";s:4:\"date\";s:21:\"27\/\/10\/\/2015 13:25:03\";}","details":{"Fecha":"2015-10-27 13:25:02","Combustible":"G95","Precio\/lt":1.124,"Litros":17.795414462081,"Precio":1.124,"IVA":4.2004296296296,"Total":20.002045855379,"Ahorro":0.17795414462081}},"amount":20.002045855379,"deliverycode":"169875"}
	2015-10-27T13:25:04+01:00 DEBUG (7): order after collect: {"receiptid":"64","receiptposid":"9085-169875","summary":{"data":"O:8:\"stdClass\":6:{s:12:\"idoffstation\";s:4:\"9085\";s:4:\"pump\";s:1:\"3\";s:8:\"fueltype\";s:3:\"G95\";s:7:\"idorder\";s:3:\"263\";s:12:\"idopystation\";s:1:\"3\";s:4:\"date\";s:21:\"27\/\/10\/\/2015 13:25:03\";}","details":{"Fecha":"2015-10-27 13:25:02","Combustible":"G95","Precio\/lt":1.124,"Litros":17.795414462081,"Precio":1.124,"IVA":4.2004296296296,"Total":20.002045855379,"Ahorro":0.17795414462081}},"taxes":{"1":{"name":"IVA","locale":"es_ES","percent":"21"}},"amount":"20","billingdata":{"billingName":"Openy 2 Fake ","billingAddress":"Av. Icaria 08005 Barcelona espa\u00f1a","billingId":"aadapterPC2-T","billingWeb":null,"billingLogo":"repsol","billingMail":"mail@openy.es","billingPhone":null},"template":null,"date":"2015-10-27 13:25:04","idpayment":"dc225982-e8ca-5c8d-9896-49af366e689d","idopystation":"3"}
	2015-10-27T13:25:04+01:00 DEBUG (7): autoCollect: {"receiptid":"64","receiptposid":"9085-169875","summary":{"data":"O:8:\"stdClass\":6:{s:12:\"idoffstation\";s:4:\"9085\";s:4:\"pump\";s:1:\"3\";s:8:\"fueltype\";s:3:\"G95\";s:7:\"idorder\";s:3:\"263\";s:12:\"idopystation\";s:1:\"3\";s:4:\"date\";s:21:\"27\/\/10\/\/2015 13:25:03\";}","details":{"Fecha":"2015-10-27 13:25:02","Combustible":"G95","Precio\/lt":1.124,"Litros":17.795414462081,"Precio":1.124,"IVA":4.2004296296296,"Total":20.002045855379,"Ahorro":0.17795414462081}},"taxes":{"1":{"name":"IVA","locale":"es_ES","percent":"21"}},"amount":"20","billingdata":{"billingName":"Openy 2 Fake ","billingAddress":"Av. Icaria 08005 Barcelona espa\u00f1a","billingId":"aadapterPC2-T","billingWeb":null,"billingLogo":"repsol","billingMail":"mail@openy.es","billingPhone":null},"template":null,"date":"2015-10-27 13:25:04","idpayment":"dc225982-e8ca-5c8d-9896-49af366e689d","idopystation":"3"}
	2015-10-27T13:25:04+01:00 DEBUG (7): {"registration_ids":["APA91bHnw9krIKPc0bXUzL1-O3jx0TZ-SvX15IfFi8ir_yxrLSDe1_kXKB-m8no8G_lwFAYNwWvsekyT9cMAC-pwM9jlUlssue1Ko4f1HDvBH6a9cFS3SFOsUf1YAeDLDXnAuMjsuEia"],"data":{"data":{"idorder":"263","idopystation":"3","pump":"3","pumpHanged":"true","timestamp":"2015-10-27 13:24:56","collect":{"receiptid":"64","receiptposid":"9085-169875","summary":{"data":"O:8:\"stdClass\":6:{s:12:\"idoffstation\";s:4:\"9085\";s:4:\"pump\";s:1:\"3\";s:8:\"fueltype\";s:3:\"G95\";s:7:\"idorder\";s:3:\"263\";s:12:\"idopystation\";s:1:\"3\";s:4:\"date\";s:21:\"27\/\/10\/\/2015 13:25:03\";}","details":{"Fecha":"2015-10-27 13:25:02","Combustible":"G95","Precio\/lt":1.124,"Litros":17.795414462081,"Precio":1.124,"IVA":4.2004296296296,"Total":20.002045855379,"Ahorro":0.17795414462081}},"taxes":{"1":{"name":"IVA","locale":"es_ES","percent":"21"}},"amount":"20","billingdata":{"billingName":"Openy 2 Fake ","billingAddress":"Av. Icaria 08005 Barcelona espa\u00f1a","billingId":"aadapterPC2-T","billingWeb":null,"billingLogo":"repsol","billingMail":"mail@openy.es","billingPhone":null},"template":null,"date":"2015-10-27 13:25:04","idpayment":"dc225982-e8ca-5c8d-9896-49af366e689d","idopystation":"3"}},"title":"Enhorabuena, repostaje con Openy finalizado!","message":"Te hemos enviado el ticket!","vibrate":1,"sound":1}}
	2015-10-27T13:25:05+01:00 DEBUG (7): done TRUE hang: 263
	2015-10-27T13:25:05+01:00 INFO (6): --------- Refuel END ---------