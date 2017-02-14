#Client Register

 -regiterid: registerId for PUSH 
 
`POST //clientregister`  
Accept: application/hal+json  
Content-Type: application/json  

####Request   
	{
	    "osversion":"Mac OS X 10.7.5 (11G63)",
	  	"lat": "41.3941772",
	    "lng": "2.2002508",
	    "registerid":"111"
	} 
	
####Response  
	{
	    "privatekey": "d62a5997-6efa-58d1-bd68-8b6a30dbc8d5",
	    "publickey": "$2y$14$39X9Wafo9Uj5YdR145QVjuiVMOprRO6lk3HOz0W3fMOvo5C0ymQN.",
	    "osversion": "Mac OS X 10.7.5 (11G63)",
	    "lat": "41.3941772",
	    "lng": "2.2002508",
	    "other": null,
	    "registerid":"111"
	    "_links": {
	        "self": {
	            "href": "http:///clientregister/d62a5997-6efa-58d1-bd68-8b6a30dbc8d5"
	        }
	    }
	}


#Register User (Crear cuenta)

![Alt text](/assets/developer/api/user-register.png "User Register"){#id .doc-image}

`POST //register`  
Accept: application/hal+json  
Content-Type: application/json  

####Request 
	{
	    "email": "@gmail.com",
		"password":"zaqwesz",
	    "first_name": "Agustin",
	    "last_name": "Calderon",
	    "phone_number":"12341234"    
	}
	
####Response  
	{
	    "email": "@gmail.com",
	    "first_name": "Agustin",
	    "last_name": "Calderon",
	    "phone_number": "12341234",
	    "token": "NmY5YjY1ZTBlMzg0Mzc0ZDQ4ZTE0YzEyM2YyMmU0ZTI=",
	    "iduser": "f7914e2b-b903-57cb-9b37-2063ea0ed1c8",
	    "_links": {
	        "self": {
	            "href": "http:///register/@gmail.com"
	        }
	    }
	}

#### Error Response: on try to register an already registered user
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

#Validate email (with emailed link)

`GET http:///verifyemail/NmY5YjY1ZTBlMzg0Mzc0ZDQ4ZTE0YzEyM2YyMmU0ZTI%3D/@gmail.com`

####Response
	{
		"result":true
	}

#Validate sms (with sms/email code)

`GET http:///verifysms/9227/f7914e2b-b903-57cb-9b37-2063ea0ed1c8`

####Response
	{
		"result":true
	}
	