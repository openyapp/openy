##POS Pump Status

## Get Pump status

 - [idoffstation]
 
`GET //opystation/pumpstatus/1`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

### Response _(showing only 3 station price)_
	{
	    "idopystation": "1",
	    "_embedded": {
	        "status": [
	            {
	                "idpump": 0,
	                "status": "00"
	            },
	            {
	                "idpump": 1,
	                "status": "00"
	            },
	            {
	                "idpump": 2,
	                "status": "00"
	            },
	            {
	                "idpump": 3,
	                "status": "00"
	            },
	            {
	                "idpump": 4,
	                "status": "00"
	            },
	            {
	                "idpump": 5,
	                "status": "00"
	            }
	        ]
	    },
	    "_links": {
	        "self": {
	            "href": "http:///opystation/pumpstatus/1"
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