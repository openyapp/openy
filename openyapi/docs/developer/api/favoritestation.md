#Favorite Station
	
### Get all favorite stations  

`GET http:///favoritestation`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Response
	{
	    "0": {
	        "idfavorite": "1",
	        "iduser": "f7914e2b-b903-57cb-9b37-2063ea0ed1c8",
	        "idoffstation": "1"
	    },
	    "1": {
	        "idfavorite": "8",
	        "iduser": "f7914e2b-b903-57cb-9b37-2063ea0ed1c8",
	        "idoffstation": "3"
	    },
	    "2": {
	        "idfavorite": "9",
	        "iduser": "b1b9e2d1-0a0d-56c6-b0eb-17913a41f514",
	        "idoffstation": "3"
	    }
	}
	
	
### Get all user favorite stations  

`GET http:///favoritestation?iduser=f7914e2b-b903-57cb-9b37-2063ea0ed1c8`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Response
	{
	    "0": {
	        "idfavorite": "1",
	        "iduser": "f7914e2b-b903-57cb-9b37-2063ea0ed1c8",
	        "idoffstation": "1"
	    },
	    "1": {
	        "idfavorite": "8",
	        "iduser": "f7914e2b-b903-57cb-9b37-2063ea0ed1c8",
	        "idoffstation": "3"
	    }
	}
	
### Get all favorite stations by idstation 

`GET http:///favoritestation?idoffstation=3`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Response
	{
	    "0": {
	        "idfavorite": "8",
	        "iduser": "f7914e2b-b903-57cb-9b37-2063ea0ed1c8",
	        "idoffstation": "3"
	    },
	    "1": {
	        "idfavorite": "9",
	        "iduser": "b1b9e2d1-0a0d-56c6-b0eb-17913a41f514",
	        "idoffstation": "3"
	    }
	}

### Set user favorite station 

`POST http:///favoritestation`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Request
	{
	    "idoffstation":"5"
	}
	
###Response
	{
	    "idoffstation": "5",
	    "iduser": "f7914e2b-b903-57cb-9b37-2063ea0ed1c8",
	    "idfavorite": "10"
	}
	
### Remove user favorite station 

`DELETE http:///favoritestation`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Request
	{
	    "idoffstation":"5"
	}
	
###Response
	204 No Content