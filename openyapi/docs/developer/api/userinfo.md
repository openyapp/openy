#Get User Info

 - Get current user info
 
`GET //oauthuser/userinfo`   
Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer 79222c5976553dd03061b635db327a73d71e634c  
	
####Response
	{
	    "username": "@gmail.com",
	    "first_name": "Agustin",
	    "last_name": "Calderon",
	    "created": "2015-07-23 10:59:22",
	    "phone_number": "3412341234",
	    "iduser": "f7914e2b-b903-57cb-9b37-2063ea0ed1c8",
	    "token": "NDhjNjY5MDc0ZmVkZTEwMTE0YzIwZTdiZDJhODRjMjg=",
	    "_links": {
	        "self": {
	            "href": "http:///oauthuser/f7914e2b-b903-57cb-9b37-2063ea0ed1c8"
	        }
	    }
	}

#Patch User Info

 - phone_number __NOT__ available. See [Change user phone](/docs/userinfo/api#change-user-phone).
 - password __NOT__ available. See [Change user password](/docs/userlogin/api#change-user-password).
 
`PATCH //oauthuser/f7914e2b-b903-57cb-9b37-2063ea0ed1c8`   
Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer 79222c5976553dd03061b635db327a73d71e634c  
	
####Request
	{
	  "phone_number":"34123123123"    
	}
	
####Response
	{
	    "iduser": "f7914e2b-b903-57cb-9b37-2063ea0ed1c8",
	    "username": "@gmail.com",
	    "first_name": "Agustin",
	    "last_name": "Calderon",
	    "phone_number": "34123123123",
	    "created": "2015-07-23 10:59:22",
	    "token": "NDhjNjY5MDc0ZmVkZTEwMTE0YzIwZTdiZDJhODRjMjg=",
	    "_links": {
	        "self": {
	            "href": "http:///oauthuser/f7914e2b-b903-57cb-9b37-2063ea0ed1c8"
	        }
	    }
	}
	
#Change User Phone

Change user phone need two process.   

 - Send a Verification code to the new phone. See [Send SMS to new phone](/docs/sms/api#sent-sms-to-new-phone).  
 - Verify the code new phone. See [Verify code](/docs/sms/api#code-validation-for-new-phone).  
 
  
