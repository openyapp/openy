#Sent SMS

`POST //sendcode`   
Accept: application/hal+json  
Content-Type: application/json  

####Request
	{
		"iduser":"b1b9e2d1-0a0d-56c6-b0eb-17913a41f514"
	}

A SMS is sent.   
In test mode, a email with the code is sent too.   
If you try more than 3 times, you get a 412 Too many intents.
	
####Response
	{
	    "result": true
	}

#Code Validation

`GET //verifysms[/:code][/:iduser]`  
Accept: application/hal+json  
Content-Type: application/json  

####Request
	GET http:///verifysms/6259/b1b9e2d1-0a0d-56c6-b0eb-17913a41f514
	
####Response
	{"result":true}

#Sent SMS to new phone
	
 - Too many intents if try to send more than 3 sms before 24h.
 
`POST //sendcodenewphone`   
Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer 79222c5976553dd03061b635db327a73d71e634c  
	
####Request
	{
	  "new_phone_number":"698785125",
	  "iduser":"b1b9e2d1-0a0d-56c6-b0eb-17913a41f514"
	}
	
####Response
	{
	    "result": true
	}
	
#### Error Response 	
	{
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-412",
	    "title": "Too many intents",
	    "status": 412,
	    "detail": "Too many validation intents"
	}
	
#Code Validation for New Phone

`GET //verifysmsnewphone[/:code][/:iduser]`  
Accept: application/hal+json  
Content-Type: application/json  

####Request
	GET http:///verifysmsnewphone/5022/b1b9e2d1-0a0d-56c6-b0eb-17913a41f514
	
####Response
	{"result":true}