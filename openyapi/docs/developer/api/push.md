#PUSH
	
### Raise Pump Push

	{
		"registration_ids":["APA91bHnw9krIKPc0bXUzL1-O3jx0TZ-SvX15IfFi8ir_yxrLSDe1_kXKB-m8no8G_lwFAYNwWvsekyT9cMAC-pwM9jlUlssue1Ko4f1HDvBH6a9cFS3SFOsUf1YAeDLDXnAuMjsuEia"],
		"data":{
			"message":{
				"idorder":"166",
				"idopystation":"1",
				"pump":"3",
				"pumpRaised":"true",
				"timestamp":"2015-10-24 10:54:55"
			},
			"title":"Has iniciado un repostaje con Openy!",
			"vibrate":1,
			"sound":1
		}
	}
	
### Hang Pump Push

	{
		"registration_ids":["APA91bHnw9krIKPc0bXUzL1-O3j"],
		"data":{
			"message":{
				"idorder":"166",
				"idopystation":"1",
				"pump":"3",
				"pumpHanged":"true",
				"timestamp":"2015-10-24 10:57:13",
				"collect":{
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
			},
			"title":"Enhorabuena, repostaje con Openy finalizado!",
			"vibrate":1,
			"sound":1
		}
	}
	