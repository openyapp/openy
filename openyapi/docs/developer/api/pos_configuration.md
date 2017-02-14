#POS Configuration

## Get 30 station price

 - [idoffstation]
 
`GET //opystation/configuration/1`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

### Response _(showing only 3 station price)_
	{
	    "idopystation": "1",
	    "idconfig": "1",
	    "currency": "euros",
	    "units": "litros",
	    "_links": {
	        "self": {
	            "href": "http:///opystation/configuration/1"
	        }
	    }
	}
	
### Error response

	{
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404",
	    "title": "Not found",
	    "status": 404,
	    "detail": "Not Opy station found"
	}