#User Login

![Alt text](/assets/developer/api/user-login.png "User Login"){#id .doc-image}

`POST //oauth`   
Accept: application/hal+json  
Content-Type: application/json  

####Request
	{
	    "username":"@gmail.com",
	    "password":"zaqwesz",
	    "grant_type":"password",
	    "client_id": "d62a5997-6efa-58d1-bd68-8b6a30dbc8d5"	    
	}
	
####Response
	{
	    "access_token": "bb2ef36d3410e5adf9558c680e2f937b52cddbd2",
	    "expires_in": 600,
	    "token_type": "Bearer",
	    "scope": null,
	    "refresh_token": "4797cbced05cf1fa84b278b84822d9be4128d20e"
	}
	
#### Error Response
	{
	    "user": {
	        "email": "devel@gmail.com",
	        "first_name": "asdasd",
	        "last_name": "asdasd",
	        "phone_number": "34123456789",
	        "token": "MTEwMDAzMDNhZTNiODE5NzRhMjE1NWI5M2U0OGQwMDY=",
	        "iduser": "1f877fa9-1c73-56fb-bcf0-373a25b4e66a",
	        "created": "2015-09-19 14:25:03",
	        "updated": "2015-09-19 14:34:57",
	        "type": "0",
	        "ip": "127.0.0.1"
	    },
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-400",
	    "title": "Bad Request",
	    "status": 400,
	    "detail": "User registered but not validated"
	}
	
#### Error Response: Try to login without validated user, 1 day after previous sms and not limit reached
	{
	    "user": {
	        "email": "devel@gmail.com",
	        "first_name": "asdasd",
	        "last_name": "asdasd",
	        "phone_number": "34123456789",
	        "token": "MTEwMDAzMDNhZTNiODE5NzRhMjE1NWI5M2U0OGQwMDY=",
	        "iduser": "1f877fa9-1c73-56fb-bcf0-373a25b4e66a",
	        "created": "2015-09-17 14:25:03",
	        "updated": "2015-09-19 14:48:52",
	        "type": "0",
	        "ip": "127.0.0.1"
	    },
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-409",
	    "title": "Conflict",
	    "status": 409,
	    "detail": "User registered but not validated. SMS sent."
	}
	
#### Error Response: Try to login without validated user but sms limit reached
	{
	    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-429",
	    "title": "Too many request",
	    "status": 429,
	    "detail": "Validation SMSs reached"
	}
		
#Refresh Token

`POST //oauth`  
Accept: application/hal+json  
Content-Type: application/json  

####Request
	{
	  	"grant_type":"refresh_token",  
		"client_id": "d62a5997-6efa-58d1-bd68-8b6a30dbc8d5",	
	  	"refresh_token":"4797cbced05cf1fa84b278b84822d9be4128d20e"
	}
	
####Response
	{
	    "access_token": "e0a6558d25c4f3cb6d9640e7e6035ab86e18b984",
	    "expires_in": 600,
	    "token_type": "Bearer",
	    "scope": null,
	    "refresh_token": "313eb1756d00457668c60c67690254d0d9186a86"
	}

#Recover Password

 - An email is sent to the user with the new password.
 - Token, refresh_token and access_token, are exprired.
 - Push the App to login page
 
`POST //recoverpassword`  
Accept: application/hal+json  
Content-Type: application/json  

####Request
	{
	    "email": "suombuel@gmail.com"
	}
	
####Response
	{
	    "email": "suombuel@gmail.com",
	    "first_name": "asdasd",
	    "last_name": "asdasd",
	    "token": "YjU4ZmVlYjhjYmY5MWVkNDhiN2NiYjBiNDU3YTZhMTA=",
	    "type": 1,
	    "_links": {
	        "self": {
	            "href": "http:///recoverpassword/suombuel@gmail.com"
	        }
	    }
	}
	
#Change user password 
 
`PATCH //preference/f7914e2b-b903-57cb-9b37-2063ea0ed1c8`   
Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer 79222c5976553dd03061b635db327a73d71e634c

####Request
	{
	  "password":"123123",
	  "newpassword":"zaqwesz"
	}
	
####Response
	{
	    "iduser": "b1b9e2d1-0a0d-56c6-b0eb-17913a41f514",
	    "username": "@gmail.com",
	    "first_name": "Agustin",
	    "last_name": "Calderon",
	    "phone_number": "34123123123",
	    "created": "2015-08-05 14:18:48",
	    "token": "MzU0NWM3MWI4YmMxZGE0NTM1OWU5ZDdlMzg0ZTQwODU=",
	    "_links": {
	        "self": {
	            "href": "http:///oauthuser/b1b9e2d1-0a0d-56c6-b0eb-17913a41f514"
	        }
	    }
	}

#User Logout  

 - The user id is get from Authorizattion Header using Bearer code 
 
`GET /revoke`   
Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer 79222c5976553dd03061b635db327a73d71e634c

####Response
	{
		"result":true
	}