#Verify Pin

 - All credit cards will be deleted if attempts_remaining = 0
 - Attempts remaining counter reset after 14 days 
 
`GET //verifypin/121212/f7914e2b-b903-57cb-9b37-2063ea0ed1c8`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984
	
####Response
	{
	    "result": true
	}
	
###Error Response: Not valid 3 to 1 attemmts remaining
	{
	    "attempts_remaining": 1,
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400",
	    "title": "Bad Request",
	    "status": 400,
	    "detail": "Not valid pin"
	}
	
###Error Response: Not valid: 0 attemmts remaining
	{
	    "attempts_remaining": 0,
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400",
	    "title": "Bad Request",
	    "status": 400,
	    "detail": "Not valid pin"
	}
	
	
	
	