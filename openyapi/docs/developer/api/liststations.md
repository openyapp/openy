#List Stations

## Paginated list stations WITH Distance and Favorites 

 - 50 items per page
 - Order by distance
 
`GET //liststations?point=41.448,2.18827`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Response _(showing only 3 stations)_	
	{
    "_links": {
        "self": {
            "href": "http:///liststations?point=41.448,2.18827&page=1"
        },
        "first": {
            "href": "http:///liststations?point=41.448,2.18827"
        },
        "last": {
            "href": "http:///liststations?point=41.448,2.18827&page=192"
        },
        "next": {
            "href": "http:///liststations?point=41.448,2.18827&page=2"
        }
    },
    "_embedded": {
        "liststations": [
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
                "distance": "0.0008596756862859952",
                "_links": {
                    "self": {
                        "href": "http:///liststations/1"
                    }
                }
            },
            {
                "idoffstation": "6256",
                "name": "REPSOL",
                "address": "CALLE GRAN DE SANT ANDREU, 513",
                "ilat": "41.44378",
                "ilng": "2.18831",
                "logoname": "repsol",
                "idopeny": "0",
                "recommended": "0",
                "favorite": "0",
                "distance": "0.4693664883117344",
                "_links": {
                    "self": {
                        "href": "http:///liststations/6256"
                    }
                }
            },
            {
                "idoffstation": "5886",
                "name": "REPSOL",
                "address": "PASEO SANTA COLOMA, 71",
                "ilat": "41.44725",
                "ilng": "2.19508",
                "logoname": "repsol",
                "idopeny": "0",
                "recommended": "0",
                "favorite": "0",
                "distance": "0.5736872269138968",
                "_links": {
                    "self": {
                        "href": "http:///liststations/5886"
                    }
                }
            }
        ]
    },
    "page_count": 192,
    "page_size": 50,
    "total_items": 9552,
    "page": 1
}



## Paginated list stations WITH Favorites 

 - 50 items per page
 - Order by idoffstation
 
`GET //liststations`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Response _(showing only 3 stations)_
	{
    "_links": {
        "self": {
            "href": "http:///liststations?page=1"
        },
        "first": {
            "href": "http:///liststations"
        },
        "last": {
            "href": "http:///liststations?page=192"
        },
        "next": {
            "href": "http:///liststations?page=2"
        }
    },
    "_embedded": {
        "liststations": [
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
                "distance": null,
                "_links": {
                    "self": {
                        "href": "http:///liststations/1"
                    }
                }
            },
            {
                "idoffstation": "2",
                "name": "AVIA",
                "address": "POLIGONO SANSINENEA ERREKA, 14",
                "ilat": "43.27356",
                "ilng": "-2.27403",
                "logoname": "avia",
                "idopeny": "0",
                "recommended": "1",
                "favorite": "1",
                "distance": null,
                "_links": {
                    "self": {
                        "href": "http:///liststations/2"
                    }
                }
            },
            {
                "idoffstation": "3",
                "name": "AVIA",
                "address": "PASEO DR. BEGIRISTAIN, 75",
                "ilat": "43.29708",
                "ilng": "-1.96625",
                "logoname": "avia",
                "idopeny": "0",
                "recommended": "1",
                "favorite": "1",
                "distance": null,
                "_links": {
                    "self": {
                        "href": "http:///liststations/3"
                    }
                }
            }
        ]
    },
    "page_count": 192,
    "page_size": 50,
    "total_items": 9552,
    "page": 1
}

## Paginated list stations 

 - Without Distance 
 - Without Favorites 
 - 50 items per page
 - Order by idoffstation
 
`GET //liststations`

Accept: application/hal+json  
Content-Type: application/json  

###Response _(showing only 3 stations)_
	{
    "_links": {
        "self": {
            "href": "http:///liststations?page=1"
        },
        "first": {
            "href": "http:///liststations"
        },
        "last": {
            "href": "http:///liststations?page=192"
        },
        "next": {
            "href": "http:///liststations?page=2"
        }
    },
    "_embedded": {
        "liststations": [
            {
                "idoffstation": "1",
                "name": "MEROIL",
                "address": "AVENIDA MERIDIANA, 666",
                "ilat": "41.448",
                "ilng": "2.18828",
                "logoname": "meroil",
                "idopeny": "1",
                "recommended": "1",
                "favorite": null,
                "distance": null,
                "_links": {
                    "self": {
                        "href": "http:///liststations/1"
                    }
                }
            },
            {
                "idoffstation": "2",
                "name": "AVIA",
                "address": "POLIGONO SANSINENEA ERREKA, 14",
                "ilat": "43.27356",
                "ilng": "-2.27403",
                "logoname": "avia",
                "idopeny": "0",
                "recommended": "1",
                "favorite": null,
                "distance": null,
                "_links": {
                    "self": {
                        "href": "http:///liststations/2"
                    }
                }
            },
            {
                "idoffstation": "3",
                "name": "AVIA",
                "address": "PASEO DR. BEGIRISTAIN, 75",
                "ilat": "43.29708",
                "ilng": "-1.96625",
                "logoname": "avia",
                "idopeny": "0",
                "recommended": "1",
                "favorite": null,
                "distance": null,
                "_links": {
                    "self": {
                        "href": "http:///liststations/3"
                    }
                }
            }
        ]
    },
    "page_count": 192,
    "page_size": 50,
    "total_items": 9552,
    "page": 1
}
	
## Resume of filters 

<table class="table table-striped">
<tr>
	<th> /liststations </th>
	<th>Header Beader</th>
	<th>Filter Point</th>
	<th>Output</th>
	
</tr>
<tr>
	<td></td>
	<td> Bearer AccessToken </td>
	<td> ?point=latitude,longitud </td>
	<td> Favorite, Distance, Recommended, Openy </td>
</tr>
<tr>
	<td></td>
	<td> Bearer AccessToken </td>
	<td> Null </td>
	<td> Favorite, Recommended, Openy </td>
</tr>
<tr>
	<td></td>
	<td> Null </td>
	<td> ?point=latitude,longitud </td>
	<td> Distance, Recommended, Openy</td>
</tr>
<tr>
	<td></td>
	<td> Null </td>
	<td> Null </td>
	<td> Recommended, Openy </td>
</tr>
</table>
	
	
## Get ONE Station Info with Distance and Favorite

`GET //liststations/1?point=41.448,2.18827`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Response

	{
	    "idoffstation": "4",
	    "name": "AVIA",
	    "address": "AVENIDA NAVARRA, 18",
	    "ilat": "43.33931",
	    "ilng": "-1.78225",
	    "logoname": "avia",
	    "idopeny": "0",
	    "recommended": "0",
	    "favorite": 0,
	    "distance": "388km",
	    "fueltypes": "1,2,3,4,7,9,10",
	    "prices": "1.299,1.369,1.139,1.169,1.138,1.442,0.569",
	    "_links": {
	        "self": {
	            "href": "http:///station/4"
	        }
	    }
	}	

## Get ONE Station Info with Favorite

`GET //liststations/1

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Response

	{
	    "idoffstation": "1",
	    "name": "MEROIL",
	    "address": "AVENIDA MERIDIANA, 666",
	    "ilat": "41.448",
	    "ilng": "2.18828",
	    "logoname": "meroil",
	    "idopeny": "1",
	    "recommended": "1",
	    "favorite": 1,
	    "distance": null,
	    "fueltypes": "1,2,3,4,7,9,10",
	    "prices": "1.299,1.369,1.139,1.169,1.138,1.442,0.569",
	    "_links": {
	        "self": {
	            "href": "http:///station/1"
	        }
	    }
	}	
	
## Get ONE Station Info without Distance nor Favorite

`GET //liststations/1

Accept: application/hal+json  
Content-Type: application/json  

###Response

	{
	    "idoffstation": "1",
	    "name": "MEROIL",
	    "address": "AVENIDA MERIDIANA, 666",
	    "ilat": "41.448",
	    "ilng": "2.18828",
	    "logoname": "meroil",
	    "idopeny": "1",
	    "recommended": "1",
	    "favorite": null,
	    "distance": null,
	    "fueltypes": "1,2,3,4,7,9,10",
	    "prices": "1.299,1.369,1.139,1.169,1.138,1.442,0.569",
	    "_links": {
	        "self": {
	            "href": "http:///station/1"
	        }
	    }
	}		
	
## Get Stations WITH Filter and Order and Ftype

### Filters 
	& filter=openy
	& filter=favorite
	& filter=recommended

### Orders 
	& order=price 
	& order=distance
	
### Ftype (fueltype)

`http:///fueltype`

	& ftype= [fuelcode]
	
![Alt text](/assets/developer/api/fueltypes.png "Fueltypes") 

`GET //liststations?point=41.448,2.18827&order=distance&filter=recommended`
	
Accept: application/hal+json  
Content-Type: application/jsown  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984

###Response

	{
	    "_links": {
	        "self": {
	            "href": "http:///liststations?point=41.448,2.18827&order=distance&filter=recommended&page=1"
	        },
	        "first": {
	            "href": "http:///liststations?point=41.448,2.18827&order=distance&filter=recommended"
	        },
	        "last": {
	            "href": "http:///liststations?point=41.448,2.18827&order=distance&filter=recommended&page=1"
	        }
	    },
	    "_embedded": {
	        "liststations": [
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
	                "distance": "0.0008596756862859952",
	                "fueltype": null,
	                "price": null,
	                "_links": {
	                    "self": {
	                        "href": "http:///liststations/1"
	                    }
	                }
	            },
	            {
	                "idoffstation": "3",
	                "name": "AVIA",
	                "address": "PASEO DR. BEGIRISTAIN, 75",
	                "ilat": "43.29708",
	                "ilng": "-1.96625",
	                "logoname": "avia",
	                "idopeny": "0",
	                "recommended": "1",
	                "favorite": "1",
	                "distance": "398.36321941438774",
	                "fueltype": null,
	                "price": null,
	                "_links": {
	                    "self": {
	                        "href": "http:///liststations/3"
	                    }
	                }
	            },
	            {
	                "idoffstation": "2",
	                "name": "AVIA",
	                "address": "POLIGONO SANSINENEA ERREKA, 14",
	                "ilat": "43.27356",
	                "ilng": "-2.27403",
	                "logoname": "avia",
	                "idopeny": "0",
	                "recommended": "1",
	                "favorite": "1",
	                "distance": "418.9988798995036",
	                "fueltype": null,
	                "price": null,
	                "_links": {
	                    "self": {
	                        "href": "http:///liststations/2"
	                    }
	                }
	            }
	        ]
	    },
	    "page_count": 1,
	    "page_size": 50,
	    "total_items": 3,
	    "page": 1
	}	
