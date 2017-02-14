#Transactions


## Get ALL transactions

- This endpoint is for DEVELOPMENT ONLY

`GET //transaction`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984



###Response _(showing less)_
	{
	    "_links": {
	        "self": {
	            "href": "http:///transaction?page=1"
	        },
	        "first": {
	            "href": "http:///transaction"
	        },
	        "last": {
	            "href": "http:///transaction?page=3"
	        },
	        "next": {
	            "href": "http:///transaction?page=2"
	        }
	    },
	    "_embedded": {
	        "transaction": [
	            {
	                "transactionid": "1",
	                "authorizationcode": "905956",
	                "merchantcode": "",
	                "amount": "53.00",
	                "idcreditcard": "90531f38-3006-54ab-8b04-bc9f08692e4c",
	                "transactionType": "O",
	                "created": "2015-08-05 13:06:51",
	                "updated": "2015-08-05 13:06:51",
	                "lastresponse": "0000",
	                "lastcode": null,
	                "terminal": "2",
	                "token": null,
	                "cvv": null,
	                "pan": null,
	                "expiry": "",
	                "secret": null,
	                "lasterror": {
	                    "text": null,
	                    "code": "default",
	                    "translations": []
	                },
	                "_links": {
	                    "self": {
	                        "href": "http:///transaction/1"
	                    }
	                }
	            },
	            {
	                "transactionid": "2",
	                "authorizationcode": "",
	                "merchantcode": "",
	                "amount": "53.00",
	                "idcreditcard": "90531f38-3006-54ab-8b04-bc9f08692e4c",
	                "transactionType": "Q",
	                "created": "2015-08-05 13:06:51",
	                "updated": null,
	                "lastresponse": "",
	                "lastcode": null,
	                "terminal": "2",
	                "token": null,
	                "cvv": null,
	                "pan": null,
	                "expiry": "",
	                "secret": null,
	                "lasterror": {
	                    "text": null,
	                    "code": "default",
	                    "translations": []
	                },
	                "_links": {
	                    "self": {
	                        "href": "http:///transaction/2"
	                    }
	                }
	            },
	            {
	                "transactionid": "20150a4f3e01",
	                "authorizationcode": "390355",
	                "merchantcode": "",
	                "amount": "0.30",
	                "idcreditcard": "0a4f3e01-f058-56ea-bc2b-4ff997a2d954",
	                "transactionType": "O",
	                "created": "2015-10-17 23:50:36",
	                "updated": "2015-10-17 23:50:36",
	                "lastresponse": "0000",
	                "lastcode": "0",
	                "terminal": "2",
	                "token": null,
	                "cvv": null,
	                "pan": null,
	                "expiry": "",
	                "secret": null,
	                "lasterror": {
	                    "text": null,
	                    "code": "default",
	                    "translations": []
	                },
	                "_links": {
	                    "self": {
	                        "href": "http:///transaction/20150a4f3e01"
	                    }
	                }
	            },
	            {
	                "transactionid": "201538e8d74f",
	                "authorizationcode": "407380",
	                "merchantcode": "",
	                "amount": "0.13",
	                "idcreditcard": "38e8d74f-7588-5abd-a3f4-0f86902972da",
	                "transactionType": "O",
	                "created": "2015-10-24 10:04:45",
	                "updated": "2015-10-24 10:04:46",
	                "lastresponse": "0000",
	                "lastcode": "0",
	                "terminal": "2",
	                "token": null,
	                "cvv": null,
	                "pan": null,
	                "expiry": "",
	                "secret": null,
	                "lasterror": {
	                    "text": null,
	                    "code": "default",
	                    "translations": []
	                },
	                "_links": {
	                    "self": {
	                        "href": "http:///transaction/201538e8d74f"
	                    }
	                }
	            },
	            {
	                "transactionid": "20157e0a1d90",
	                "authorizationcode": "805597",
	                "merchantcode": "",
	                "amount": "0.22",
	                "idcreditcard": "7e0a1d90-151c-5fab-b99f-f2fdfe4b28f5",
	                "transactionType": "O",
	                "created": "2015-10-19 00:27:06",
	                "updated": "2015-10-19 00:27:08",
	                "lastresponse": "0000",
	                "lastcode": "0",
	                "terminal": "2",
	                "token": null,
	                "cvv": null,
	                "pan": null,
	                "expiry": "",
	                "secret": null,
	                "lasterror": {
	                    "text": null,
	                    "code": "default",
	                    "translations": []
	                },
	                "_links": {
	                    "self": {
	                        "href": "http:///transaction/20157e0a1d90"
	                    }
	                }
	            },
	            {
	                "transactionid": "20159225c221",
	                "authorizationcode": "390050",
	                "merchantcode": "",
	                "amount": "0.79",
	                "idcreditcard": "9225c221-366b-59aa-ac67-fa1fc67c3052",
	                "transactionType": "O",
	                "created": "2015-10-17 18:46:54",
	                "updated": "2015-10-17 18:46:54",
	                "lastresponse": "0000",
	                "lastcode": "0",
	                "terminal": "2",
	                "token": null,
	                "cvv": null,
	                "pan": null,
	                "expiry": "",
	                "secret": null,
	                "lasterror": {
	                    "text": null,
	                    "code": "default",
	                    "translations": []
	                },
	                "_links": {
	                    "self": {
	                        "href": "http:///transaction/20159225c221"
	                    }
	                }
	            }           
	        ]
	    },
	    "page_count": 3,
	    "page_size": 25,
	    "total_items": 61,
	    "page": 1
	}
	
	
	
## Get Transactions filtered by

- idcreditcard

`GET //transaction?idcreditcard=38e8d74f-7588-5abd-a3f4-0f86902972da`

Accept: application/hal+json  
Content-Type: application/json  
Authorization: Bearer e0a6558d25c4f3cb6d9640e7e6035ab86e18b984  



###Response _(showing less)_
	{
	    "_links": {
	        "self": {
	            "href": "http:///transaction?idcreditcard=38e8d74f-7588-5abd-a3f4-0f86902972da&page=1"
	        },
	        "first": {
	            "href": "http:///transaction?idcreditcard=38e8d74f-7588-5abd-a3f4-0f86902972da"
	        },
	        "last": {
	            "href": "http:///transaction?idcreditcard=38e8d74f-7588-5abd-a3f4-0f86902972da&page=1"
	        }
	    },
	    "_embedded": {
	        "transaction": [
	            {
	                "transactionid": "201538e8d74f",
	                "authorizationcode": "407380",
	                "merchantcode": "",
	                "amount": "0.13",
	                "idcreditcard": "38e8d74f-7588-5abd-a3f4-0f86902972da",
	                "transactionType": "O",
	                "created": "2015-10-24 10:04:45",
	                "updated": "2015-10-24 10:04:46",
	                "lastresponse": "0000",
	                "lastcode": "0",
	                "terminal": "2",
	                "token": null,
	                "cvv": null,
	                "pan": null,
	                "expiry": "",
	                "secret": null,
	                "lasterror": {
	                    "text": null,
	                    "code": "default",
	                    "translations": []
	                },
	                "_links": {
	                    "self": {
	                        "href": "http:///transaction/201538e8d74f"
	                    }
	                }
	            },
	            {
	                "transactionid": "2015OPYpt39",
	                "authorizationcode": "407398",
	                "merchantcode": "",
	                "amount": "20.00",
	                "idcreditcard": "38e8d74f-7588-5abd-a3f4-0f86902972da",
	                "transactionType": "O",
	                "created": "2015-10-24 10:22:34",
	                "updated": "2015-10-24 10:22:35",
	                "lastresponse": "0000",
	                "lastcode": "0",
	                "terminal": "2",
	                "token": null,
	                "cvv": null,
	                "pan": null,
	                "expiry": "",
	                "secret": null,
	                "lasterror": {
	                    "text": null,
	                    "code": "default",
	                    "translations": []
	                },
	                "_links": {
	                    "self": {
	                        "href": "http:///transaction/2015OPYpt39"
	                    }
	                }
	            },
	            {
	                "transactionid": "2015OPYpt40",
	                "authorizationcode": null,
	                "merchantcode": "",
	                "amount": "20.00",
	                "idcreditcard": "38e8d74f-7588-5abd-a3f4-0f86902972da",
	                "transactionType": "O",
	                "created": "2015-10-24 10:25:35",
	                "updated": null,
	                "lastresponse": null,
	                "lastcode": null,
	                "terminal": "2",
	                "token": null,
	                "cvv": null,
	                "pan": null,
	                "expiry": "",
	                "secret": null,
	                "lasterror": {
	                    "text": null,
	                    "code": "default",
	                    "translations": []
	                },
	                "_links": {
	                    "self": {
	                        "href": "http:///transaction/2015OPYpt40"
	                    }
	                }
	            }
	        ]
	    },
	    "page_count": 1,
	    "page_size": 25,
	    "total_items": 6,
	    "page": 1
	}
	
	