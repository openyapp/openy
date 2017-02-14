##POS Pump

## Get Pos Pumps

 - [idoffstation]
 
`GET //opystation/pump/1?point=41.3941772,2.2002508`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

### Response 
	{
	    "idopystation": "1",
	    "station": {
	        "idcompany": "1",
	        "idoffstation": "1",
	        "name": "MEROIL",
	        "logoname": "meroil",
	        "address": "AVENIDA MERIDIANA, 666",
	        "idopeny": "1",
	        "ilat": "41.448",
	        "ilng": "2.18828",
	        "distance": "10m",
	        "idistance": 10.000671992203
	    },
	    "ipumps": null,
	    "_embedded": {
	        "pumps": [
	            {
	                "idPump": "1",
	                "status": {
	                    "state": "locked",
	                    "mode": "prepaid"
	                },
	                "product": [
	                    "GOA",
	                    "G95"
	                ]
	            },
	            {
	                "idPump": "2",
	                "status": {
	                    "state": "locked",
	                    "mode": "prepaid"
	                },
	                "product": [
	                    "GOA",
	                    "G95"
	                ]
	            },
	            {
	                "idPump": "3",
	                "status": {
	                    "state": "locked",
	                    "mode": "prepaid"
	                },
	                "product": [
	                    "GOA",
	                    "G95"
	                ]
	            },
	            {
	                "idPump": "4",
	                "status": {
	                    "state": "locked",
	                    "mode": "prepaid"
	                },
	                "product": [
	                    "GOA",
	                    "G95"
	                ]
	            }
	        ]
	    },
	    "_links": {
	        "self": {
	            "href": "http:///opystation/pump/1"
	        }
	    }
	}
	
`GET //opystation/pump/1`
	
### Error response: NOT Point set
	{
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404",
	    "title": "Not found",
	    "status": 404,
	    "detail": "Not location set"
	}

`GET //opystation/pump/6?point=41.448,2.18828`
	
 - The idStation is not a OPENY station 
	
### Error response: NOT Openy Station
	{
	    "stationData": {
	        "name": "CONDIS",
	        "logoname": "no-logo",
	        "address": "POLIGONO DE LA AIGUETA, S/N",
	        "idopeny": "0"
	    },
	    "closeStationData": {
	        "idoffstation": "1",
	        "name": "MEROIL",
	        "address": "AVENIDA MERIDIANA, 666",
	        "ilat": "41.448",
	        "ilng": "2.18828",
	        "logoname": "meroil",
	        "idopeny": "1",
	        "recommended": "1",
	        "favorite": "1",
	        "distance": "0.14334796176302783",
	        "fueltype": null,
	        "price": null
	    },
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404",
	    "title": "Not found",
	    "status": 404,
	    "detail": "Not Opy station found"
	}
	
`GET //opystation/pump/9085?point=41.448,2.18828`
	
 - The idStation is a OPENY station, but the point is so far 	
 	
### Error response: Not point on request
	{
	    "stationData": {
	        "idstation": "3",
	        "idcompany": "2",
	        "idoffstation": "9085",
	        "name": "REPSOL",
	        "logoname": "repsol",
	        "address": "AVENIDA CALVO SOTELO, 5",
	        "idopeny": "1",
	        "ilat": "43.36683",
	        "ilng": "-8.41342",
	        "distance": "895km",
	        "idistance": 895467.12259983
	    },
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400",
	    "title": "Not found",
	    "status": 400,
	    "detail": "You are not close to this station"
	}
	
