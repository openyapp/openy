#Price

## Get 30 station price

`GET //price`

Accept: application/hal+json  
Content-Type: application/json  

###Response _(showing only 3 station price)_
	{
	    {
	    "0": {
	        "idoffstation": "1",
	        "fueltypes": "1,2,3,4,7,9,10",
	        "prices": "1.299,1.369,1.139,1.169,1.138,1.442,0.569"
	    },
	    "1": {
	        "idoffstation": "2",
	        "fueltypes": "1,3,7,9",
	        "prices": "1.275,1.125,1.113,1.269"
	    },
	    "2": {
	        "idoffstation": "3",
	        "fueltypes": "1,2,3,4,7,9",
	        "prices": "1.319,1.438,1.179,1.244,1.179,1.345"
	    }
	}
	
	
## Get All station price

NOTE: Expect large output (35K results). To test with curl with output to a file (be aware that not all python tools are utf-8 compatible).
	
	curl -s -H "Accept: application/hal+json" http:///price?psize=all | python -mjson.tool > stations.json


`GET //station?pzise=all`

Accept: application/hal+json  
Content-Type: application/json

###Response _(showing only 3 station price)_

	{
	    "0": {
	        "fueltypes": "1,2,3,4,7,9,10", 
	        "idoffstation": "1", 
	        "prices": "1.299,1.369,1.139,1.169,1.138,1.442,0.569"
	    }, 
	    "1": {
	        "fueltypes": "1,3,7,9", 
	        "idoffstation": "2", 
	        "prices": "1.275,1.125,1.113,1.269"
	    }, 
	    "10": {
	        "fueltypes": "1,2,3,4,7,9", 
	        "idoffstation": "11", 
	        "prices": "1.335,1.444,1.199,1.264,1.219,1.447"
	    }    
	}