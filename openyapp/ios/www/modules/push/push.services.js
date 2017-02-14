angular.module('starter.services')

.service('pushService', ['$rootScope', '$state', '$ionicHistory', 'store', function ($rootScope, $state, $ionicHistory, store) {
    var pushEvent = function (response) {
        console.log('response', response);
        console.log('$state', $state);
        var data;

        //pass string data to object
        if(typeof response.additionalData.data !== 'undefined')  data = response.additionalData.data;
        console.log('message', data);


        /*-RAISE-*/
        /*additionalData: {

            "data":{
                        "idorder":"263",
                        "idopystation":"3",
                        "pump":"3",
                        "pumpRaised":"true",
                        "timestamp":"2015-10-27 13:24:40"
                    },
            "title":"Has iniciado un repostaje con Openy!",
            "message":"Orden: 263","vibrate":1,"sound":1
            }
        }*/
        /*-foreground-*/
        /*response = {
            additionalData: {
                Objectcollapse_key: "do_not_collapse",
                foreground: true,
                from: "754117127892",
                vibrate: "1"
            },
            message: "{"idopystation":"1","idorder":"190","pumpRaised":"true","pump":"3","timestamp":"2015-10-24 13:43:27"}",
            sound: "1",
            title: "Has iniciado un repostaje con Openy!"
        };*/

        /*-HANG-*/
        /*-foreground-*/
        /*response = {
            additionalData:{
                collapse_key: "do_not_collapse",
                foreground: true,
                from: "754117127892",
                vibrate: "1"
            },
            message: "{"pumpHanged":"true","idopystation":"1","idorder":"190","pump":"3","collect":{"summary":{"data":"O:8:\"stdClass\":5:{s:12:\"idopystation\";s:1:\"1\";s:4:\"pump\";s:1:\"3\";s:8:\"fueltype\";s:3:\"G95\";s:7:\"idorder\";s:2:\"88\";s:4:\"date\";s:21:\"20\\\/\\\/09\\\/\\\/2015 17:29:22\";}","details":{"Precio\\\/lt":1.099,"Litros":26.572187776794,"Fecha":"2015-09-20 17:29:22","IVA":3.15,"Total":15,"Precio":1.099,"Ahorro":15}},"template":null,"date":"2015-09-20 17:29:23","amount":"15","billingdata":{"billingWeb":null,"billingId":"00000000-T","billingLogo":"meroil","billingName":"Openy Fake Station","billingAddress":"Av. Icaria 08000 Barcelona espiña","billingMail":"mail@openy.es","billingPhone":null},"idopystation":"1","receiptposid":"f80958a1-c037-530d-a95a-33f0e7079c08","_links":{"self":{"href":"http:\\\/\\\/\\\/collect"}},"idpayment":"b6f2e4ea-3c92-5ae1-9b9e-2f1312ad4aa3","taxes":{"1":{"name":"IVA","locale":"es_ES","percent":"21"}},"receiptid":"4"},"timestamp":"2015-10-24 13:43:54"}",
            sound: "1",
            title: "Enhorabuena, repostaje con Openy finalizado!"
        }
        };*/

        var checkBillsStoreRepeat = function(receiptid){
            if(store.get('bills')){
                var billsStore = store.get('bills');
                for(var i = 0; i < billsStore.length; i++){
                    if(billsStore[i].idorder == data.idorder) {
                        data = {};
                        data.collect = billsStore[i];
                        return true;
                    }
                }
            }
            return false;
        };

        var pumpPushForeground = function(){
            if($state.current.name == 'app.refuel.waiting'){

                if(typeof data.pumpRaised !== 'undefined' && data.pumpRaised === 'true'){
                    console.log('pushData FOREGRAOUND RAISE');
                    if(!checkBillsStoreRepeat()) $rootScope.$broadcast('pushRaise');
                    else $state.go('app.refuel.completed', {collect: angular.toJson(data.collect)});
                }

                else if(typeof data.pumpHanged !== 'undefined'){
                    console.log('pushData FOREGRAOUND HANG', data.collect);
                    $rootScope.$broadcast('pushHang', data.collect);
                }

            }else{
                pumpPushBackground();
            }
        };

        var pumpPushBackground = function(){

            if(typeof data.pumpRaised !== 'undefined' && data.pumpRaised === 'true'){
                console.log('pushData CLOSE RAISE');
                $state.go('app.refuel.waiting', {collect: '', type: 1})
            }

            else if(typeof data.pumpHanged !== 'undefined'){
                console.log('pushData CLOSE HANG', data.collect);
                $ionicHistory.nextViewOptions({
                    disableAnimate: false,
                    disableBack: true
                });
                $state.go('app.refuel.completed', {collect: angular.toJson(data.collect)});
            }

        };

        /*-----------------------------------*/

        //if pumpRaise FALSE always error
        if(typeof data.pumpRaised !== 'undefined' && data.pumpRaised === 'false'){
            $state.go('app.refuel.waiting', {collect: '', type: 3});
        }

        //APP FOREGRAOUND
        if(typeof response.additionalData !== 'undefined' && response.additionalData.foreground){
            console.log('pushData FOREGRAOUND', response);
            pumpPushForeground();
        //APP BACKGROUND
        } else{
            console.log('pushData BACKGROUND', response);
            pumpPushBackground();
        }

    };
    return {
        pushEvent: pushEvent
    }
}])

;
