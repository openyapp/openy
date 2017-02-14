#Sendfeedback

## Send Feedback

 - Sent feedback to: contact@openy.es, feedback@openy.es, pos@openy.es, dev@openy.es
 - ONLY SEND emails to the above emails
 
`POS //sendfeedback`  
Accept: application/hal+json    	
Content-Type: application/json  

####Request   
	{
	    "to":"dev@openy.es",
	    "from":"@gmail.com",
	    "subject":"my subject",
		"body":"testing"	      
	} 
	
####Response  
	{"result":"true"}
	
####Error Response   
	{
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400",
	    "title": "Bad Request",
	    "status": 400,
	    "detail": "Receiver not recognized"
	}
