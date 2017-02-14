#Stations


## Get 30 stations WITH Favorites

- _SELECT "off_station".* FROM "off_station"_

- Get the stations with current authorized user favorites

`GET //station`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984



###Response _(showing only 3 stations)_
	{
	    "0": {
	        "idoffstation": "1",
	        "name": "MEROIL",
	        "address": "AVENIDA MERIDIANA, 666",
	        "ilat": "41.448",
	        "ilng": "2.18828",
	        "logoname": "meroil",
	        "idopeny": "1",
	        "recommended": "1",
	        "favorite": "1"
	    },
	    "1": {
	        "idoffstation": "2",
	        "name": "AVIA",
	        "address": "POLIGONO SANSINENEA ERREKA, 14",
	        "ilat": "43.27356",
	        "ilng": "-2.27403",
	        "logoname": "avia",
	        "idopeny": "0",
	        "recommended": "1",
	        "favorite": "1"
	    },
	    "2": {
	        "idoffstation": "3",
	        "name": "AVIA",
	        "address": "PASEO DR. BEGIRISTAIN, 75",
	        "ilat": "43.29708",
	        "ilng": "-1.96625",
	        "logoname": "avia",
	        "idopeny": "0",
	        "recommended": null,
	        "favorite": "0"
	    }
	}
	
	
	
## Get 30 stations

- _SELECT "off_station".* FROM "off_station"_

`GET //station`

Accept: application/hal+json  
Content-Type: application/json  



###Response _(showing only 3 stations)_
	{
	    "0": {
	        "idoffstation": "1",
	        "name": "MEROIL",
	        "address": "AVENIDA MERIDIANA, 666",
	        "ilat": "41.448",
	        "ilng": "2.18828",
	        "logoname": "meroil",
	        "idopeny": "1",
	        "recommended": "1"
	    },
	    "1": {
	        "idoffstation": "2",
	        "name": "AVIA",
	        "address": "POLIGONO SANSINENEA ERREKA, 14",
	        "ilat": "43.27356",
	        "ilng": "-2.27403",
	        "logoname": "avia",
	        "idopeny": "0",
	        "recommended": "1"
	    },
	    "2": {
	        "idoffstation": "3",
	        "name": "AVIA",
	        "address": "PASEO DR. BEGIRISTAIN, 75",
	        "ilat": "43.29708",
	        "ilng": "-1.96625",
	        "logoname": "avia",
	        "idopeny": "0",
	        "recommended": null
	    }
	}
	
	
## Get All stations

NOTE: Expect large output (10K results). To test with curl with output to a file (be aware that not all python tools are utf-8 compatible).
	
	curl -s -H "Accept: application/hal+json" http:///station?psize=all | python -mjson.tool > stations.json

- _SELECT "off_station".* FROM "off_station"_

`GET //station?pzise=all`

Accept: application/hal+json  
Content-Type: application/json  

###Response _(showing only 3 stations)_

	{
	    "0": {
	        "idoffstation": "1",
	        "name": "MEROIL",
	        "address": "AVENIDA MERIDIANA, 666",
	        "ilat": "41.448",
	        "ilng": "2.18828",
	        "logoname": "meroil",
	        "idopeny": "1",
	        "recommended": "1"
	    },
	    "1": {
	        "idoffstation": "2",
	        "name": "AVIA",
	        "address": "POLIGONO SANSINENEA ERREKA, 14",
	        "ilat": "43.27356",
	        "ilng": "-2.27403",
	        "logoname": "avia",
	        "idopeny": "0",
	        "recommended": "1"
	    },
	    "2": {
	        "idoffstation": "3",
	        "name": "AVIA",
	        "address": "PASEO DR. BEGIRISTAIN, 75",
	        "ilat": "43.29708",
	        "ilng": "-1.96625",
	        "logoname": "avia",
	        "idopeny": "0",
	        "recommended": null
	    }	    
	}
	
## Get 30 new stations after DATE (2015-06-21)

- _SELECT "off_station".* FROM "off_station" WHERE "created" > '2015-06-21'_

`GET //station?action=new`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Response _(showing only 3 stations)_

	{
	    "0": {
	        "idoffstation": "9436",
	        "name": "G2",
	        "address": "CALLE BOO, 52",
	        "ilat": "43.41222",
	        "ilng": "-3.8435",
	        "logoname": "no-logo",
	        "idopeny": "0",
	        "recommended": null
	    },
	    "1": {
	        "idoffstation": "9437",
	        "name": "E.S. JARAFUEL",
	        "address": "AVENIDA PAIS VALENCIANO, S/N",
	        "ilat": "39.13831",
	        "ilng": "-1.07572",
	        "logoname": "no-logo",
	        "idopeny": "0",
	        "recommended": null
	    },
	    "2": {
	        "idoffstation": "9438",
	        "name": "GASOLINERA SANTANA",
	        "address": "AVDA. 1º MAYO S/N",
	        "ilat": "38.10429",
	        "ilng": "-3.62245",
	        "logoname": "no-logo",
	        "idopeny": "0",
	        "recommended": null
	    }	    
	}

	
## Get 30 updated stations

- _SELECT "off_station".* FROM "off_station" WHERE "updated" IS NOT NULL{}_

`GET //station?action=update`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Response _(showing only 3 stations)_

	{
	    "0": {
	        "idoffstation": "9436",
	        "name": "G2",
	        "address": "CALLE BOO, 52",
	        "ilat": "43.41222",
	        "ilng": "-3.8435",
	        "logoname": "no-logo",
	        "idopeny": "0",
	        "recommended": null
	    },
	    "1": {
	        "idoffstation": "9437",
	        "name": "E.S. JARAFUEL",
	        "address": "AVENIDA PAIS VALENCIANO, S/N",
	        "ilat": "39.13831",
	        "ilng": "-1.07572",
	        "logoname": "no-logo",
	        "idopeny": "0",
	        "recommended": null
	    },
	    "2": {
	        "idoffstation": "9438",
	        "name": "GASOLINERA SANTANA",
	        "address": "AVDA. 1º MAYO S/N",
	        "ilat": "38.10429",
	        "ilng": "-3.62245",
	        "logoname": "no-logo",
	        "idopeny": "0",
	        "recommended": null
	    }	    
	}
	
## Get 30 updated stations after DATE

- _SELECT "off_station".* FROM "off_station" WHERE "updated" >= '2015-06-21' AND "updated" IS NOT NULL{}_

`GET //station?action=update&date=2015-06-21`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Response _(showing only 3 stations)_

	{
	    "0": {
	        "idoffstation": "9436",
	        "name": "G2",
	        "address": "CALLE BOO, 52",
	        "ilat": "43.41222",
	        "ilng": "-3.8435",
	        "logoname": "no-logo",
	        "idopeny": "0",
	        "recommended": null
	    },
	    "1": {
	        "idoffstation": "9437",
	        "name": "E.S. JARAFUEL",
	        "address": "AVENIDA PAIS VALENCIANO, S/N",
	        "ilat": "39.13831",
	        "ilng": "-1.07572",
	        "logoname": "no-logo",
	        "idopeny": "0",
	        "recommended": null
	    },
	    "2": {
	        "idoffstation": "9438",
	        "name": "GASOLINERA SANTANA",
	        "address": "AVDA. 1º MAYO S/N",
	        "ilat": "38.10429",
	        "ilng": "-3.62245",
	        "logoname": "no-logo",
	        "idopeny": "0",
	        "recommended": null
	    }	    
	}

## Get ONE station info without Distance

`GET //station/1`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Response _(showing only 3 stations)_

	{
	    "idoffstation": "1",
	    "name": "MEROIL",
	    "address": "AVENIDA MERIDIANA, 666",
	    "ilat": "41.448",
	    "ilng": "2.18828",
	    "logoname": "meroil",
	    "idopeny": "1",
	    "recommended": "1",
	    "favorite": "1",
	    "fueltypes": "1,2,3,4,7,9,10",
	    "prices": "1.299,1.369,1.139,1.169,1.138,1.442,0.569"
	}	
	
## Get ONE station info with Distance

`GET //station/1?point=41.448,2.18827`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Response _(showing only 3 stations)_

	{
	    "idoffstation": "1",
	    "name": "MEROIL",
	    "address": "AVENIDA MERIDIANA, 666",
	    "ilat": "41.448",
	    "ilng": "2.18828",
	    "logoname": "meroil",
	    "idopeny": "1",
	    "recommended": "1",
	    "favorite": "1",
	    "fueltypes": "1,2,3,4,7,9,10",
	    "prices": "1.299,1.369,1.139,1.169,1.138,1.442,0.569",
	    "distance": "1m"
	}	
	
	