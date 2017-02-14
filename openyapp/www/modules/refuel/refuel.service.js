var refuelService = function refuelService($q, $http, CONFIG, $filter) {
    return {
        getClosest: function (idstation, position) {
            var deferred = $q.defer();
            $http({
                    url: CONFIG.APIURL + 'opystation' + '/closest/' + idstation + '?point=' + position.lat + ',' + position.lng,
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .success(function (data) {
                    deferred.resolve(data.closeStationData);
                })
                .error(function (error) {
                    deferred.reject(error);
                });
            return deferred.promise;
        },
        getSupplies: function (idstation, position) {
            var deferred = $q.defer();
            $http({
                    url: CONFIG.APIURL + 'opystation' + '/pump/' + idstation + '?point=' + position.lat + ',' + position.lng,
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .success(function (data) {
                    deferred.resolve(data);
                })
                .error(function (error) {
                    if (error.status == 400) {
                        deferred.reject({
                            text: 'Estás demasiado lejos de los surtidores, tienes que acercarte más',
                            station: error.stationData
                        });
                    } else if (error.status == 404) {
                        if (error.detail == 'Not location set') {
                            deferred.reject({
                                text: 'No hemos podido localizar tu posición'
                            });
                        }
                        // not send idstation or idstation wrong
                        else if (error.detail == 'Page not found') {
                            deferred.reject({
                                text: 'No hemos podido localizar esta gasolinera'
                            });
                        } else if (error.detail == 'Not Opy station found') {
                            deferred.reject({
                                text: 'No te hemos podido ubicar en una gasolinera con pago con Openy activado. <strong>Tienes una a ' + $filter('number')(error.closeStationData.distance, 0) + ' km</strong>.',
                                station: error.closeStationData
                            });
                        }
                    } else {
                        //another error
                        deferred.reject({
                            text: 'Ha ocurrido un error'
                        });
                    }
                });
            return deferred.promise;
        },
        getPrices: function (idstation) {
            var deferred = $q.defer();
            $http({
                    url: CONFIG.APIURL + 'opystation' + '/price/' + idstation,
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .success(function (data) {
                    deferred.resolve(data);
                })
                .error(function (error) {
                    deferred.reject('No hemos encontrado ninguna estación con Openy');
                });
            return deferred.promise;
        },
        refuel: function (refueling) {
            var deferred = $q.defer(),
                error = 0,
                attemps,
                collect = {
                    "idoffstation": "",
                    "pump": "", 
                    "fueltype": "",
                    "idorder": ""
                };
            
            var ok = function(response){
                collect = {
                    "idoffstation": response.refuel.idoffstation,
                    "pump": response.refuel.pump, 
                    "fueltype": response.refuel.fueltype,
                    "idorder": response._embedded.order.idorder
                };
                deferred.resolve(collect);
            }
            
            var error = function(response){
                //TYPE = 1 => NO PIN
                if(response.datails !== 'undefined' && angular.isArray(response.datails) && response.details[0].chain !== 'undefined' && response.details[0].chain.userPin !== 'undefined' && !response.details[0].chain.userPin.satisfied && response.details[0].chain.userPin.remaining_attempts == 'unknown') error = 1;
                    
                //TYPE = 2 => BAD PIN (check attemps)
                else if(response.datails !== 'undefined' && angular.isArray(response.datails) && response.details[0].chain !== 'undefined' && response.details[0].chain.userPin !== 'undefined' && !response.details[0].chain.userPin.satisfied && response.details[0].chain.userPin.remaining_attempts !== 'unknown') {
                    error = 2;
                    attemps = response.details[0].chain.userPin.remaining_attempts;                    
                }
                
                //TYPE = 3 => OK PIN && !ANTIFRAUDPIN
                else if(response.datails !== 'undefined' && angular.isArray(response.datails) && response.details[0].chain !== 'undefined' && response.details[0].chain.antifraudPin !== 'undefined' && response.details[0].chain.userPin.satisfied && response.details[0].chain.antifraudPin.remaining_attempts == 'unknown') {
                    error = 3;
                }
                
                //TYPE = 4 => OK PIN && BAD ANTIFRAUDPIN (check attemps)
                else if(response.datails  !== 'undefined' && angular.isArray(response.datails) && response.details[0].chain !== 'undefined' && response.details[0].chain.antifraudPin !== 'undefined' && response.details[0].chain.userPin.satisfied && response.details[0].chain.antifraudPin.remaining_attempts != 'unknown'){
                     error = 4;
                     attemps = response.details[0].chain.antifraudPin.remaining_attempts;                    
                }
                
                //TYPE = 5 => ERROR CREDIT CARD
                else if(response.detail !== 'undefined' && response.detail  === 'Bank error') {
                    error = 5;
                    console.log('error', error);
                }
                
                deferred.reject({error: error, attemps: attemps});

            }
            
            /*PRODUCTION*/
            
            $http({
                    url: CONFIG.APIURL + 'refuel',
                    method: 'POST',
                    data: refueling,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .success(function (response) {
                    ok(response);
                })
                .error(function (error) {
                    error(error);
                });
            
            
            
                /*DEPLOY*/
                //var response = {};
                //OK, SHOW POMODORO && WAIT FOR 1º PUSH
                /*response = {
                    "allowRaisePump": true,
                    "refuel": {
                        "idoffstation": "1",
                        "pump": "3",
                        "fueltype": "G95",
                        "amount": "30",
                        "price": "1.099",
                        "email": "@gmail.com",
                        "antifraudPin": "5310",
                        "userPin": "1234",
                        "toRefuel": 45,
                        "idopystation": "1"
                    },
                    "security": {
                        "chain": {
                            "userPin": {
                                "satisfied": true,
                                "remaining_attempts": 4
                            }
                        },
                        "verification": true
                    },
                    "_embedded": {
                        "order": {
                            "idorder": "37",
                            "idopystation": "1",
                            "summary": null,
                            "amount": "30",
                            "iduser": "f7914e2b-b903-57cb-9b37-2063ea0ed1c8",
                            "idpayment": null,
                            "paymentmethod": "1",
                            "deliverycode": null,
                            "orderstatus": {
                                "status": 3,
                                "idorder": "37",
                                "paymentoperationid": null,
                                "lastresponse": null,
                                "lastcode": null,
                                "codemsg": null,
                                "openymsg": null
                            },
                            "created": "2015-10-21 17:56:02",
                            "updated": "2015-10-21 17:56:03",
                            "_links": {
                                "self": {
                                    "href": "http:///orders/37"
                                }
                            }
                        },
                        "price": {
                            "idPromotion": "14",
                            "units": 26.572187776794,
                            "originalPricePerUnit": "1.129000000",
                            "value": 30,
                            "discountPerUnit": 0.03,
                            "discountPercentage": 0.5,
                            "pricePerUnit": 1.099,
                            "promPricePorcentage": 15,
                            "promPricePerIUnit": 29.202834366696,
                            "promoUnits": 27.29754322111,
                            "promType": "discount",
                            "discount": 15,
                            "priceToPay": 15,
                            "_links": []
                        },
                        "amount": {
                            "code": 200,
                            "response": {
                                "result": "OK",
                                "request": {
                                    "command": "004",
                                    "pump": "3",
                                    "product": 5,
                                    "price": "1.099",
                                    "amount": 45
                                }
                            },
                            "_links": []
                        }
                    },
                    "_links": {
                        "self": {
                            "href": "http:///refuel"
                        }
                    }
                };*/
                
                //NO PIN
                /*response = {
                    "details": [
                        {
                            "chain": {
                                "userPin": {
                                    "satisfied": false,
                                    "remaining_attempts": "unknown"
                                }
                            },
                            "verification": false
                        }
                    ],
                    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-307",
                    "title": "Temporary redirect",
                    "status": 307,
                    "detail": "Security Chain Required"
                };*/

                //BAD PIN
                /*response = {
                    "details": [
                        {
                            "chain": {
                                "userPin": {
                                    "satisfied": false,
                                    "remaining_attempts": 3
                                }
                            },
                            "verification": false
                        }
                    ],
                    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-307",
                    "title": "Temporary redirect",
                    "status": 307,
                    "detail": "Security Chain Required"
                };*/

                //BAD PIN && FREEZING PIN
                /*response = {
                    "details": [
                        {
                            "chain": {
                                "userPin": {
                                    "satisfied": false,
                                    "remaining_attempts": 0
                                }
                            },
                            "verification": false
                        }
                    ],
                    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-307",
                    "title": "Temporary redirect",
                    "status": 307,
                    "detail": "Security Chain Required"
                };*/

                //GOOD PIN && !ANTIFRAUDPIN
                /*response = {
                    "details": [
                        {
                            "chain": {
                                "userPin": {
                                    "satisfied": true,
                                    "remaining_attempts": 3
                                },
                                "antifraudPin": {
                                    "satisfied": false,
                                    "remaining_attempts": "unknown"
                                }
                            },
                            "verification": false
                        }
                    ],
                    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-307",
                    "title": "Temporary redirect",
                    "status": 307,
                    "detail": "Security Chain Required"
                };*/

                //GOD PIN && BAD ANTIFRAUDPIN
                /*response = {
                    "details": [
                        {
                            "chain": {
                                "userPin": {
                                    "satisfied": true,
                                    "remaining_attempts": 3
                                },
                                "antifraudPin": {
                                    "satisfied": false,
                                    "remaining_attempts": 3
                                }
                            },
                            "verification": false
                        }
                    ],
                    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-307",
                    "title": "Temporary redirect",
                    "status": 307,
                    "detail": "Security Chain Required"
                };*/

                //GOD PIN && BAD ANTIFRAUDPIN FREEZING ACCOUNT
                /*response = {
                    "details": [
                        {
                            "chain": {
                                "userPin": {
                                    "satisfied": true,
                                    "remaining_attempts": 3
                                },
                                "antifraudPin": {
                                    "satisfied": false,
                                    "remaining_attempts": 0
                                }
                            },
                            "verification": false
                        }
                    ],
                    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-307",
                    "title": "Temporary redirect",
                    "status": 307,
                    "detail": "Security Chain Required"
                };*/

                //GOOD PIN && GOOD ANTIFRAUDPIN && !CREDITCARD
                /*response = {
                    "details": {
                        "errorCode": "0",
                        "errorMessage": null
                    },
                    "type": "http://www.seouniv.com/2012/10/http-status-codes.html#http-status-code-402",
                    "title": "Payment Required",
                    "status": 402,
                    "detail": "Bank error"
                };*/
            
            
            /*if(response.allowRaisePump !== 'undefined' && response.allowRaisePump) {
                ok(response);
            } else{
                error(response);
            }*/
            
            return deferred.promise;
        },
        collect: function(collect){
            var deferred = $q.defer();
            /*
            DEPLOY
            */
             /*var dataResponse = {
                    "receiptid": "4",
                    "receiptposid": "f80958a1-c037-530d-a95a-33f0e7079c08",
                    "summary": {
                        "data": "O:8:\"stdClass\":5:{s:12:\"idopystation\";s:1:\"1\";s:4:\"pump\";s:1:\"3\";s:8:\"fueltype\";s:3:\"G95\";s:7:\"idorder\";s:2:\"88\";s:4:\"date\";s:21:\"20//09//2015 17:29:22\";}",
                        "details": {
                            "Fecha": "2015-09-20 17:29:22",
                            "Precio/lt": 1.099,
                            "Litros": 26.572187776794,
                            "Precio": 1.099,
                            "IVA": 3.15,
                            "Total": 15,
                            "Ahorro": 15
                        }
                    },
                    "taxes": {
                        "1": {
                            "name": "IVA",
                            "locale": "es_ES",
                            "percent": "21"
                        }
                    },
                    "amount": "15",
                    "billingdata": {
                        "billingName": "Openy Fake Station",
                        "billingAddress": "Av. Icaria 08000 Barcelona espiña",
                        "billingId": "00000000-T",
                        "billingWeb": null,
                        "billingLogo": "meroil",
                        "billingMail": "mail@openy.es",
                        "billingPhone": null
                    },
                    "template": null,
                    "date": "2015-09-20 17:29:23",
                    "idpayment": "b6f2e4ea-3c92-5ae1-9b9e-2f1312ad4aa3",
                    "idopystation": "1",
                    "_links": {
                        "self": {
                            "href": "http:///collect"
                        }
                    }
                };*/
                /*var receipt = {};                
                receipt = dataResponse.summary.details;                
                receipt.receiptid = dataResponse.receiptid;
                //fecha Math.round(new Date("2013/09/05 15:34:00").getTime()/1000)
                deferred.resolve(receipt);*/
            
            
            /*-PRODUCTION NEED AN UPDATE-*/ 
            
            $http({
                url: CONFIG.APIURL + 'collect',
                method: 'POST',
                data: collect,
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .success(function (dataResult) {
                deferred.resolve(dataResult);
            })
            .error(function (error) {
                deferred.reject(error);
            });
            return deferred.promise;
        
        }
    }
}

angular.module('starter')
    .service('refuelService', ['$q', '$http', 'CONFIG', '$filter', refuelService])

;