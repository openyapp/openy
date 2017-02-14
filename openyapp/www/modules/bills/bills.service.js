var billsService = function billsService($q, $http, CONFIG){    
    return {    
        get: function (page){            
            if(!page) page = 1;
            var deferred = $q.defer();
            $http({
                    url: CONFIG.APIURL+"receipts?page="+page,
                    method:  "GET",                    
                    headers: {
                        'Content-Type': 'application/json'
                    }
            }).success(function(response){
                console.log('response', response);
                if(typeof response._embedded !== 'undefined' && typeof response._embedded.receipts !== 'undefined'){
                    
                    var receiptsAll = response._embedded.receipts;
                    var receiptTotal = response.total_items;
                    var receipts = [];
                    console.log('receiptsAll', receiptsAll);
                    for(var i = 0; i < receiptsAll.length; i++){
                        //var receiptData = angular.fromJson(receiptsAll[i].summary.data);
                        receipts[i] = {
                            "receiptid": receiptsAll[i].receiptid,
                            "Fecha": receiptsAll[i].summary.details.Fecha,
                            "timestamp": Math.round(new Date(receiptsAll[i].summary.details.Fecha).getTime()),
                            "Combustible": receiptsAll[i].summary.details.Combustible,                            
                            "Litros": receiptsAll[i].summary.details.Litros,
                            "Precio": receiptsAll[i].summary.details.Precio,
                            "IVA": receiptsAll[i].summary.details.IVA,
                            "Total": receiptsAll[i].summary.details.Total,
                            "Ahorro": receiptsAll[i].summary.details.Ahorro,
                            /*"idorder": receiptData.idorder,
                            "idoffstation": receiptData.idoffstation,*/
                            "name": receiptsAll[i].billingdata.billingName,
                            "logo": receiptsAll[i].billingdata.billingLogo,
                        };
                    }
                    console.log('receipts', receipts);
                    deferred.resolve({receipts:receipts, total: receiptTotal});    
                } else {
                    deferred.reject('error');    
                }                
            }).error(function(error){
                deferred.reject('error');
            });
            return deferred.promise;
        },
        getInvoice: function (id){            
            var deferred = $q.defer();
            $http({
                    url: CONFIG.APIURL+"invoice?id="+id,
                    method:  "GET",                    
                    headers: {
                        'Content-Type': 'application/json'
                    }
            }).success(function(response){
                console.log('response', response);                
                deferred.resolve(response);    
            }).error(function(error){
                deferred.reject('error');
            });
            return deferred.promise;
        }
            
    }
};
        
/* 

{
   "_links":{
      "self":{
         "href":"http:\/\/\/receipts?page=1"
      },
      "first":{
         "href":"http:\/\/\/receipts"
      },
      "last":{
         "href":"http:\/\/\/receipts?page=1"
      }
   },
   "_embedded":{
      "receipts":[
         {
            "receiptid":"30",
            "summary":{
               "data":"O:8:\u0022stdClass\u0022:6:{s:12:\u0022idoffstation\u0022;s:4:\u00229085\u0022;s:4:\u0022pump\u0022;s:1:\u00221\u0022;s:8:\u0022fueltype\u0022;s:3:\u0022G95\u0022;s:7:\u0022idorder\u0022;s:3:\u0022164\u0022;s:12:\u0022idopystation\u0022;s:1:\u00223\u0022;s:4:\u0022date\u0022;s:20:\u002230\/\/10\/\/2015 0:22:44\u0022;}",
               "details":{
                  "Fecha":"2015-10-29 23:46:51",
                  "Combustible":"G95",
                  "Precio\/lt":"1.129000000",
                  "Litros":"22.14000",
                  "Precio":"1.129000000",
                  "IVA":5.25,
                  "Total":"25.00",
                  "Ahorro":0
               }
            },
            "billingdata":{
               "billingName":"Elemental Media",
               "billingAddress":"Av. Icaria 205 2-2 08005 Barcelona Espa\u00f1a",
               "billingId":"B65909566",
               "billingWeb":null,
               "billingLogo":"repsol",
               "billingMail":null,
               "billingPhone":null
            },
            "date":"2015-10-30 00:22:44",
            "_links":{
               "self":{
                  "href":"http:\/\/\/receipts\/30"
               }
            }
         },
         {
            "receiptid":"28",
            "summary":{
               "data":"O:8:\u0022stdClass\u0022:6:{s:12:\u0022idoffstation\u0022;s:4:\u00229085\u0022;s:4:\u0022pump\u0022;s:1:\u00222\u0022;s:8:\u0022fueltype\u0022;s:3:\u0022G95\u0022;s:7:\u0022idorder\u0022;s:3:\u0022162\u0022;s:12:\u0022idopystation\u0022;s:1:\u00223\u0022;s:4:\u0022date\u0022;s:20:\u002230\/\/10\/\/2015 0:12:07\u0022;}",
               "details":{
                  "Fecha":"2015-10-29 23:36:13",
                  "Combustible":"G95",
                  "Precio\/lt":"1.129000000",
                  "Litros":"22.14000",
                  "Precio":"1.129000000",
                  "IVA":5.25,
                  "Total":"25.00",
                  "Ahorro":0
               }
            },
            "billingdata":{
               "billingName":"Elemental Media",
               "billingAddress":"Av. Icaria 205 2-2 08005 Barcelona Espa\u00f1a",
               "billingId":"B65909566",
               "billingWeb":null,
               "billingLogo":"repsol",
               "billingMail":null,
               "billingPhone":null
            },
            "date":"2015-10-30 00:12:07",
            "_links":{
               "self":{
                  "href":"http:\/\/\/receipts\/28"
               }
            }
         },
         {
            "receiptid":"27",
            "summary":{
               "data":"O:8:\u0022stdClass\u0022:6:{s:12:\u0022idoffstation\u0022;s:4:\u00229085\u0022;s:4:\u0022pump\u0022;s:1:\u00222\u0022;s:8:\u0022fueltype\u0022;s:3:\u0022G95\u0022;s:7:\u0022idorder\u0022;s:3:\u0022160\u0022;s:12:\u0022idopystation\u0022;s:1:\u00223\u0022;s:4:\u0022date\u0022;s:20:\u002230\/\/10\/\/2015 0:00:09\u0022;}",
               "details":{
                  "Fecha":"2015-10-29 23:24:15",
                  "Combustible":"G95",
                  "Precio\/lt":"1.129000000",
                  "Litros":"22.14000",
                  "Precio":"1.129000000",
                  "IVA":5.25,
                  "Total":"25.00",
                  "Ahorro":0
               }
            },
            "billingdata":{
               "billingName":"Elemental Media",
               "billingAddress":"Av. Icaria 205 2-2 08005 Barcelona Espa\u00f1a",
               "billingId":"B65909566",
               "billingWeb":null,
               "billingLogo":"repsol",
               "billingMail":null,
               "billingPhone":null
            },
            "date":"2015-10-30 00:00:09",
            "_links":{
               "self":{
                  "href":"http:\/\/\/receipts\/27"
               }
            }
         },
         {
            "receiptid":"26",
            "summary":{
               "data":"O:8:\u0022stdClass\u0022:6:{s:12:\u0022idoffstation\u0022;s:4:\u00229085\u0022;s:4:\u0022pump\u0022;s:1:\u00221\u0022;s:8:\u0022fueltype\u0022;s:3:\u0022G95\u0022;s:7:\u0022idorder\u0022;s:3:\u0022159\u0022;s:12:\u0022idopystation\u0022;s:1:\u00223\u0022;s:4:\u0022date\u0022;s:21:\u002229\/\/10\/\/2015 23:48:53\u0022;}",
               "details":{
                  "Fecha":"2015-10-29 23:13:01",
                  "Combustible":"G95",
                  "Precio\/lt":"1.129000000",
                  "Litros":"13.29000",
                  "Precio":"1.129000000",
                  "IVA":3.15,
                  "Total":"15.00",
                  "Ahorro":0
               }
            },
            "billingdata":{
               "billingName":"Elemental Media",
               "billingAddress":"Av. Icaria 205 2-2 08005 Barcelona Espa\u00f1a",
               "billingId":"B65909566",
               "billingWeb":null,
               "billingLogo":"repsol",
               "billingMail":null,
               "billingPhone":null
            },
            "date":"2015-10-29 23:48:53",
            "_links":{
               "self":{
                  "href":"http:\/\/\/receipts\/26"
               }
            }
         },
         {
            "receiptid":"25",
            "summary":{
               "data":"O:8:\u0022stdClass\u0022:6:{s:12:\u0022idoffstation\u0022;s:4:\u00229085\u0022;s:4:\u0022pump\u0022;s:1:\u00223\u0022;s:8:\u0022fueltype\u0022;s:3:\u0022G95\u0022;s:7:\u0022idorder\u0022;s:3:\u0022158\u0022;s:12:\u0022idopystation\u0022;s:1:\u00223\u0022;s:4:\u0022date\u0022;s:21:\u002229\/\/10\/\/2015 23:46:17\u0022;}",
               "details":{
                  "Fecha":"2015-10-29 23:10:23",
                  "Combustible":"G95",
                  "Precio\/lt":"1.129000000",
                  "Litros":"35.86000",
                  "Precio":"1.129000000",
                  "IVA":8.5029,
                  "Total":"40.49",
                  "Ahorro":0
               }
            },
            "billingdata":{
               "billingName":"Elemental Media",
               "billingAddress":"Av. Icaria 205 2-2 08005 Barcelona Espa\u00f1a",
               "billingId":"B65909566",
               "billingWeb":null,
               "billingLogo":"repsol",
               "billingMail":null,
               "billingPhone":null
            },
            "date":"2015-10-29 23:46:17",
            "_links":{
               "self":{
                  "href":"http:\/\/\/receipts\/25"
               }
            }
         },
         {
            "receiptid":"24",
            "summary":{
               "data":"O:8:\u0022stdClass\u0022:6:{s:12:\u0022idoffstation\u0022;s:4:\u00229085\u0022;s:4:\u0022pump\u0022;s:1:\u00224\u0022;s:8:\u0022fueltype\u0022;s:3:\u0022G95\u0022;s:7:\u0022idorder\u0022;s:3:\u0022157\u0022;s:12:\u0022idopystation\u0022;s:1:\u00223\u0022;s:4:\u0022date\u0022;s:21:\u002229\/\/10\/\/2015 23:32:26\u0022;}",
               "details":{
                  "Fecha":"2015-10-29 22:56:31",
                  "Combustible":"G95",
                  "Precio\/lt":"1.129000000",
                  "Litros":"8.86000",
                  "Precio":"1.129000000",
                  "IVA":2.1,
                  "Total":"10.00",
                  "Ahorro":0
               }
            },
            "billingdata":{
               "billingName":"Elemental Media",
               "billingAddress":"Av. Icaria 205 2-2 08005 Barcelona Espa\u00f1a",
               "billingId":"B65909566",
               "billingWeb":null,
               "billingLogo":"repsol",
               "billingMail":null,
               "billingPhone":null
            },
            "date":"2015-10-29 23:32:26",
            "_links":{
               "self":{
                  "href":"http:\/\/\/receipts\/24"
               }
            }
         },
         {
            "receiptid":"23",
            "summary":{
               "data":"O:8:\u0022stdClass\u0022:6:{s:12:\u0022idoffstation\u0022;s:4:\u00229085\u0022;s:4:\u0022pump\u0022;s:1:\u00223\u0022;s:8:\u0022fueltype\u0022;s:3:\u0022G95\u0022;s:7:\u0022idorder\u0022;s:3:\u0022156\u0022;s:12:\u0022idopystation\u0022;s:1:\u00223\u0022;s:4:\u0022date\u0022;s:21:\u002229\/\/10\/\/2015 23:27:26\u0022;}",
               "details":{
                  "Fecha":"2015-10-29 22:51:33",
                  "Combustible":"G95",
                  "Precio\/lt":"1.129000000",
                  "Litros":"17.71000",
                  "Precio":"1.129000000",
                  "IVA":4.2,
                  "Total":"20.00",
                  "Ahorro":0
               }
            },
            "billingdata":{
               "billingName":"Elemental Media",
               "billingAddress":"Av. Icaria 205 2-2 08005 Barcelona Espa\u00f1a",
               "billingId":"B65909566",
               "billingWeb":null,
               "billingLogo":"repsol",
               "billingMail":null,
               "billingPhone":null
            },
            "date":"2015-10-29 23:27:27",
            "_links":{
               "self":{
                  "href":"http:\/\/\/receipts\/23"
               }
            }
         },
         {
            "receiptid":"22",
            "summary":{
               "data":"O:8:\u0022stdClass\u0022:6:{s:12:\u0022idoffstation\u0022;s:4:\u00229085\u0022;s:4:\u0022pump\u0022;s:1:\u00224\u0022;s:8:\u0022fueltype\u0022;s:3:\u0022G95\u0022;s:7:\u0022idorder\u0022;s:3:\u0022155\u0022;s:12:\u0022idopystation\u0022;s:1:\u00223\u0022;s:4:\u0022date\u0022;s:21:\u002229\/\/10\/\/2015 23:22:40\u0022;}",
               "details":{
                  "Fecha":"2015-10-29 22:46:46",
                  "Combustible":"G95",
                  "Precio\/lt":"1.129000000",
                  "Litros":"17.71000",
                  "Precio":"1.129000000",
                  "IVA":4.2,
                  "Total":"20.00",
                  "Ahorro":0
               }
            },
            "billingdata":{
               "billingName":"Elemental Media",
               "billingAddress":"Av. Icaria 205 2-2 08005 Barcelona Espa\u00f1a",
               "billingId":"B65909566",
               "billingWeb":null,
               "billingLogo":"repsol",
               "billingMail":null,
               "billingPhone":null
            },
            "date":"2015-10-29 23:22:40",
            "_links":{
               "self":{
                  "href":"http:\/\/\/receipts\/22"
               }
            }
         },
         {
            "receiptid":"21",
            "summary":{
               "data":"O:8:\u0022stdClass\u0022:6:{s:12:\u0022idoffstation\u0022;s:4:\u00229085\u0022;s:4:\u0022pump\u0022;s:1:\u00223\u0022;s:8:\u0022fueltype\u0022;s:3:\u0022G95\u0022;s:7:\u0022idorder\u0022;s:3:\u0022153\u0022;s:12:\u0022idopystation\u0022;s:1:\u00223\u0022;s:4:\u0022date\u0022;s:21:\u002229\/\/10\/\/2015 22:41:46\u0022;}",
               "details":{
                  "Fecha":"2015-10-29 22:05:53",
                  "Combustible":"G95",
                  "Precio\/lt":"1.129000000",
                  "Litros":"17.71000",
                  "Precio":"1.129000000",
                  "IVA":4.2,
                  "Total":"20.00",
                  "Ahorro":0
               }
            },
            "billingdata":{
               "billingName":"Elemental Media",
               "billingAddress":"Av. Icaria 205 2-2 08005 Barcelona Espa\u00f1a",
               "billingId":"B65909566",
               "billingWeb":null,
               "billingLogo":"repsol",
               "billingMail":null,
               "billingPhone":null
            },
            "date":"2015-10-29 22:41:46",
            "_links":{
               "self":{
                  "href":"http:\/\/\/receipts\/21"
               }
            }
         },
         {
            "receiptid":"19",
            "summary":{
               "data":"O:8:\u0022stdClass\u0022:6:{s:12:\u0022idoffstation\u0022;s:4:\u00229085\u0022;s:4:\u0022pump\u0022;s:1:\u00223\u0022;s:8:\u0022fueltype\u0022;s:3:\u0022G95\u0022;s:7:\u0022idorder\u0022;s:3:\u0022149\u0022;s:12:\u0022idopystation\u0022;s:1:\u00223\u0022;s:4:\u0022date\u0022;s:21:\u002229\/\/10\/\/2015 20:29:48\u0022;}",
               "details":{
                  "Fecha":"2015-10-29 19:53:54",
                  "Combustible":"G95",
                  "Precio\/lt":1.124,
                  "Litros":57.821869488536,
                  "Precio":1.124,
                  "IVA":13.648274074074,
                  "Total":64.991781305115,
                  "Ahorro":0.57821869488536
               }
            },
            "billingdata":{
               "billingName":"Elemental Media",
               "billingAddress":"Av. Icaria 205 2-2 08005 Barcelona Espa\u00f1a",
               "billingId":"B65909566",
               "billingWeb":null,
               "billingLogo":"repsol",
               "billingMail":null,
               "billingPhone":null
            },
            "date":"2015-10-29 20:29:48",
            "_links":{
               "self":{
                  "href":"http:\/\/\/receipts\/19"
               }
            }
         },
         {
            "receiptid":"18",
            "summary":{
               "data":"O:8:\u0022stdClass\u0022:6:{s:12:\u0022idoffstation\u0022;s:4:\u00229085\u0022;s:4:\u0022pump\u0022;s:1:\u00224\u0022;s:8:\u0022fueltype\u0022;s:3:\u0022G95\u0022;s:7:\u0022idorder\u0022;s:3:\u0022146\u0022;s:12:\u0022idopystation\u0022;s:1:\u00223\u0022;s:4:\u0022date\u0022;s:21:\u002229\/\/10\/\/2015 20:19:06\u0022;}",
               "details":{
                  "Fecha":"2015-10-29 19:43:12",
                  "Combustible":"G95",
                  "Precio\/lt":1.124,
                  "Litros":22.239858906526,
                  "Precio":1.124,
                  "IVA":5.2494962962963,
                  "Total":24.997601410935,
                  "Ahorro":0.22239858906526
               }
            },
            "billingdata":{
               "billingName":"Elemental Media",
               "billingAddress":"Av. Icaria 205 2-2 08005 Barcelona Espa\u00f1a",
               "billingId":"B65909566",
               "billingWeb":null,
               "billingLogo":"repsol",
               "billingMail":null,
               "billingPhone":null
            },
            "date":"2015-10-29 20:19:07",
            "_links":{
               "self":{
                  "href":"http:\/\/\/receipts\/18"
               }
            }
         }
      ]
   },
   "page_count":1,
   "page_size":25,
   "total_items":11,
   "page":1
}
    

*/
    
angular.module('starter')
    .factory('billsService', ['$q', '$http', 'CONFIG', billsService]);
