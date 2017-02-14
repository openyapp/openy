##POS Ping

## Get 30 station price

 - [idoffstation]
 
`GET //opystation/ping/1`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

### Response _(showing only 3 station price)_
	{
	    "ack datetime": "2015/08/20 11:48:56"
	}
	
### Error response
	{
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-404",
	    "title": "Not found",
	    "status": 404,
	    "detail": "Not Opy station found"
	}