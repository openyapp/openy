##POS Closest Gas Station Info

## Get Closest Pos Info in a Openy Station

 - [idoffstation]
 - [point]
 
`GET //opystation/closest/1?point=41.3941772,2.2002508`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

### Response 
	{
	    "idopystation": "1",
	    "station": {
	        "idstation": "1",
	        "idcompany": "1",
	        "idoffstation": "1",
	        "name": "MEROIL",
	        "logoname": "meroil",
	        "address": "AVENIDA MERIDIANA, 666",
	        "idopeny": "1",
	        "ilat": "41.448",
	        "ilng": "2.18828",
	        "distance": null,
	        "idistance": null
	    },
	    "closeStationData": {
	        "idoffstation": "5295",
	        "name": "SHELL VILLA OLIMPICA",
	        "address": "PASEO CALVELL, 2",
	        "ilat": "41.39903",
	        "ilng": "2.20825",
	        "logoname": "shell",
	        "idopeny": "0",
	        "recommended": "0",
	        "favorite": "0",
	        "distance": "0.28946921267697756",
	        "fueltype": null,
	        "price": null
	    },
	    "ipumps": null,
	    "_links": {
	        "self": {
	            "href": "http:///opystation/closest/1"
	        }
	    }
	}
	
## Get Closest Pos Info in NOT Openy Station

 - [idoffstation]
 - [point]
 
`GET //opystation/closest/22?point=41.3941772,2.2002508`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

### Response
	{
	    "idopystation": "22",
	    "station": {
	        "name": "ESTACIO SERVEI OLIANA",
	        "logoname": "no-logo",
	        "address": "CARRETERA C-14 KM. 141,000",
	        "idopeny": "0"
	    },
	    "closeStationData": {
	        "idoffstation": "5295",
	        "name": "SHELL VILLA OLIMPICA",
	        "address": "PASEO CALVELL, 2",
	        "ilat": "41.39903",
	        "ilng": "2.20825",
	        "logoname": "shell",
	        "idopeny": "0",
	        "recommended": "0",
	        "favorite": "0",
	        "distance": "0.28946921267697756",
	        "fueltype": null,
	        "price": null
	    },
	    "ipumps": null,
	    "_links": {
	        "self": {
	            "href": "http:///opystation/closest/22"
	        }
	    }
	}

## Get Closest Pos Info in NOT Existing station

 - [idoffstation] = 0
 - [point]
 
`GET //opystation/closest/0?point=41.3941772,2.2002508`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

### Response
	{
	    "idopystation": "0",
	    "station": false,
	    "closeStationData": {
	        "idoffstation": "5295",
	        "name": "SHELL VILLA OLIMPICA",
	        "address": "PASEO CALVELL, 2",
	        "ilat": "41.39903",
	        "ilng": "2.20825",
	        "logoname": "shell",
	        "idopeny": "0",
	        "recommended": "0",
	        "favorite": "0",
	        "distance": "0.28946921267697756",
	        "fueltype": null,
	        "price": null
	    },
	    "ipumps": null,
	    "_links": {
	        "self": {
	            "href": "http:///opystation/closest/0"
	        }
	    }
	}
			
### Error response: NOT Point set
	{
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404",
	    "title": "Not found",
	    "status": 404,
	    "detail": "Not location set"
	}
	

		