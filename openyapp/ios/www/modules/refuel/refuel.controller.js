/**
 * [[Description]]
 * @param {Object}   $scope               [[Description]]
 * @param {[[Type]]} $ionicScrollDelegate [[Description]]
 */
var refuelCtrl = function refuelCtrl($scope, $rootScope, $state, $ionicPopup, $ionicPlatform, $ionicHistory, $ionicScrollDelegate, $ionicSideMenuDelegate, store, stationsService, MapService, refuelService) {
    'use estrict';

    var vm = this,
        initialized = false,
        myPosition = {},
        station = {},
        myPopup,
        popUpopen,
        prePump = [],
        priceFueltypeObject = {},
        pricesApi = {},
        sendRefuelData = {};
    
        //var effects
    var elements,
        thisElement,
        thisElementWidth,
        offsetLeft,
        positionScroll,
        btn,
        btnElements,
        maskWidth,
        positionBtn,
        btnSendContainer,
        dragRightGesture,
        dragEndGesture,
        pumpDiv,
        fueltypeDiv,
        amountDiv;
    vm.hiddenRefuelHeader = false;
    vm.refuelingLoading = true;
    vm.finish = false;
    vm.loading = false;
    vm.pumps = [];
    vm.amounts = [];
    vm.fueltypes = [];
    vm.promotion = {};
    vm.refueling = {};
    /*-deploy-*/
    /*vm.refueling = {
        "idoffstation": "",
        "pump":"", 
        "fueltype":"",
        "amount":"",
        "price":"",
        "email": "",
        "antifraudPin":"",
        "userPin":""
    };*/
    
    vm.callRefuelService = function(refueling){
        console.log('refueling', refueling);
        var refuelErrorResponse = function (errorRefuel){
            console.log('errorRefuel', errorRefuel);
             
            //TYPE = 1 => NO PIN
            //TYPE = 2 => BAD PIN (check attemps)
            //TYPE = 3 => OK PIN && !ANTIFRAUDPIN
            //TYPE = 4 => OK PIN && BAD ANTIFRAUDPIN (check attemps)
            //TYPE = 7 => ERROR CREDIT CARD

             var errorsAttemps = function(){
                 myPopup = $ionicPopup.show({
                    title: '<i class="icon ion-alert-circled openy fsBig"></i>',
                    template: 'PIN erróneo, por favor vuelva a intentarlo. Le quedan ' + errorRefuel.attemps + ' intentos',
                    scope: $scope,
                    buttons: [
                        {
                            text: 'Cancelar',
                            type: 'positive',
                            onTap: function(e){
                                myPopup.close();
                                $ionicHistory.nextViewOptions({
                                    disableAnimate: false,
                                    disableBack: true
                                });
                                $state.go('app.refuel.select');
                            }
                        },
                        {
                            text: 'Aceptar'
                        }
                    ]
                });
             };

            if(errorRefuel.error && errorRefuel.error == 1){                
                $state.go('app.refuel.pin', {refueling: angular.toJson(refueling)});
            }
            else if(errorRefuel.error && errorRefuel.error == 2){
                if(errorRefuel.attemps === 0){
                    store.remove('myCreditCard');
                    $rootScope.myCard = false;
                    myPopup = $ionicPopup.show({
                        title: '<i class="icon ion-alert-circled openy fsBig"></i>',
                        template: 'No hemos podido verificar tu PIN por tu seguridad hemos eliminado todas tus tarjetas',
                        scope: $scope,
                        buttons: [
                            {
                                text: 'Aceptar',
                                type: 'positive',
                                onTap: function(e){
                                    myPopup.close();
                                    $ionicHistory.nextViewOptions({
                                        disableAnimate: false,
                                        disableBack: true
                                    });
                                    //elimino todas las tarjetas
                                    //if(store.get('settings'))
                                    $state.go('app.stations');
                                }
                            }
                        ]
                    });   
                } else {
                    errorsAttemps();
                }            

            } else if(errorRefuel.error && errorRefuel.error == 3){
                console.log('refueling', refueling);
                $state.go('app.refuel.antifraudpin', {refueling: angular.toJson(refueling)});
            } else if(errorRefuel.error && errorRefuel.error == 4){
                if(errorRefuel.attemps === 0){
                    $rootScope = {};
                    store.remove('myPaymentPin');
                    store.remove('myUser');
                    store.remove('expires_in');
                    store.remove('keepSession');
                    store.remove('myInvoice');
                    store.remove('myCreditCard');
                    store.remove('refresh_token');
                    store.remove('token');
                    myPopup = $ionicPopup.show({
                        title: '<i class="icon ion-alert-circled openy fsBig"></i>',
                        template: 'No hemos podido verificar tu PIN. Tu pedido ha sido cancelado y tu cuenta ha sido bloqueada. Puedes contactar con Openy para más seguridad.',
                        scope: $scope,
                        buttons: [
                            {
                                text: 'Aceptar',
                                type: 'positive',
                                onTap: function(e){
                                    myPopup.close();
                                    $ionicHistory.nextViewOptions({
                                        disableAnimate: false,
                                        disableBack: true
                                    });
                                    //elimino todas las tarjetas
                                    //if(store.get('settings'))
                                    $state.go('app.stations');
                                }
                            }
                        ]
                    });
                    updateBackButton();
                } else {
                    errorsAttemps(); 
                }
            } else if(errorRefuel.error && errorRefuel.error == 5){
                window.plugins.toast.showLongBottom('No se ha podido realizar la compra. Error en su entidad');
                $state.go('app.station', {station: vm.stationInfo});
            } else {
                window.plugins.toast.showLongBottom('Ha ocuurido un error inesperado, inténtelo más tarde');
                ionicHistory.nextViewOptions({
                    disableAnimate: false,
                    disableBack: true
                });
                $state.go('app.refuel.select');
            }
        };

        refuelService.refuel(vm.refueling).then(function(refuelOk){
            console.log('refuelOk', refuelOk);
            $ionicHistory.nextViewOptions({
                disableAnimate: false,
                disableBack: true
            });
            $state.go('app.refuel.waiting', {collect: angular.toJson(refuelOk)});
        }, function(errorRefuel){
            console.log('errorRefuel', errorRefuel);
            refuelErrorResponse(errorRefuel);
        }).then(function(){
            vm.loading = false;
            if(btn !== 'undefined' && btn) btn.style.webkitTransform = 'translate3d(0px, 0px, 0px) scale(1)';
        });
    
    };
    
    var updateBackButton = function(){
        $ionicHistory.nextViewOptions({
            disableAnimate: false,
            disableBack: true
        });
        $ionicPlatform.onHardwareBackButton(function onBackKeyDown(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            e.stopPropagation();
            $state.go('app.stations');
            return false;
        });
    };

    var getStation = function (idstation, myPosition) {
        vm.refueling.idoffstation = idstation;
        
        refuelService.getSupplies(idstation, myPosition).then(function (stationApi) {
            vm.stationInfo = stationApi.station;
            console.log('stationApi', stationApi);
            //init fuelTypes width all fueltypes of this station
            stationsService.getStationFuel(idstation).then(function(fueltypes){
                console.log('fueltypes', fueltypes);            
                for(var i = 0; i < fueltypes.length; i++){
                    vm.fueltypes.push(fueltypes[i].fuelcode);
                }
            });
            
            // introduce all available pumps
            if (stationApi._embedded.pumps.length > 0) {        
                var pumpsNew = stationApi._embedded.pumps;
                prePump = [];
                console.log('pumpsNew', pumpsNew);
                for (var i = 0; i < pumpsNew.length; i++) {
                    if (pumpsNew[i].status.state && pumpsNew[i].status.mode && pumpsNew[i].status.state == 'locked' && pumpsNew[i].status.mode == 'prepaid') {
                        prePump.push({id:pumpsNew[i].idPump, fueltypes:pumpsNew[i].product});
                    }
                }
                angular.copy(prePump, vm.pumps);
                console.log('vm.pumps', vm.pumps);
                if(prePump.length < 1){
                     window.plugins.toast.showLongBottom('No hay ningún surtidor operativo, vuelve a intentarlo más tarde');
                    $state.go('app.stations');
                }
                console.log('pumpsok',  vm.pumps);
            } else {
                if(prePump.length < 1){
                     window.plugins.toast.showLongBottom('No hay ningún surtidor operativo, vuelve a intentarlo más tarde');
                    $state.go('app.stations');
                }
            }
            
            //introduce all prices 5 by 5
            for(var i = 1; i < 100; i++){
                if(i%5 == 0) vm.amounts.push(i);
                if(i > 95){
                    vm.amounts.push(99);
                    break;
                }
            }
            
            //get prices && discounts
            refuelService.getPrices(idstation).then(function (pricesApiResponse) {
                pricesApi = pricesApiResponse;                
                console.log('getPrices', pricesApi._embedded.prices);
                if(typeof pricesApi._embedded === 'undefined') window.plugins.toast.showLongBottom('Ha ocurrido un error, inténtelo más tarde'); 
                /*
                
                */
            }, function (pricesError) {
                window.plugins.toast.showLongBottom('Ha ocurrido un error, inténtelo más tarde');  
            }).then(function(){
                vm.refuelingLoading = false;
            });
            
        }, function (suppliesError) {
            console.log('suppliesError', suppliesError);                       
            console.log('buttons', buttons);
            if(typeof suppliesError.text !== 'undefined' && suppliesError.text == 'Ha ocurrido un error'){
                window.plugins.toast.showLongBottom('Ha ocurrido un error, inténtalo más tarde');
                $state.go('app.stations');
            }
            else{
                popUpopen = true;
                var buttons = [
                    {
                        text: 'Ver mapa',
                        type: 'positive',
                        onTap: function (e) {
                            myPopup.close();
                            $state.go('app.stations');
                        }
                    }
                ];
                if (suppliesError.station) {
                    buttons.splice(1, 0, {
                        text: 'Llévame',
                        type: 'positive',
                        onTap: function (e) {
                            myPopup.close();
                            plugin.google.maps.external.launchNavigation({
                                "from": new plugin.google.maps.LatLng(myPosition.lat, myPosition.lng),
                                //"to": new plugin.google.maps.LatLng(stations[0].lat, stations[0].lng)
                                "to": new plugin.google.maps.LatLng(suppliesError.station.ilat, suppliesError.station.ilng)
                            });
                        }
                    })
                }
                myPopup = $ionicPopup.show({
                    title: '<i class="icon ion-alert-circled openy fsBig"></i>',
                    template: suppliesError.text,
                    scope: $scope,
                    buttons: buttons
                });   
            }            
            console.log('popup', myPopup);
            vm.refuelingLoading = false;
            updateBackButton();          
        });
    };
    
    //when click in an refuel option
    vm.selectRefueling = function(handler, value, $index){

        //ScrollEFFECT
        elements = eval(handler + 'Div.__content.children');
        console.dir('pumpDiv',pumpDiv);
        thisElement = elements[$index];
        thisElementWidth = thisElement.scrollWidth;
        elements[0].style.marginLeft = (maskWidth / 2 - thisElementWidth / 2) + 'px';
        elements[elements.length - 1].style.marginRight = (maskWidth / 2 - thisElementWidth / 2) + 'px';
        offsetLeft = thisElement.offsetLeft;
        positionScroll = -(-offsetLeft + (maskWidth / 2 - thisElementWidth / 2));
        $ionicScrollDelegate.$getByHandle(handler).scrollTo(positionScroll, 0, true);

        console.log('handler', handler);
        console.log('value', value);

        if(handler == "pump"){
            vm.refueling[handler] = value.id;
            vm.fueltypes = value.fueltypes;
        }            

        if(handler == "fueltype"){            
            vm.refueling.amount = '';
            vm.refueling[handler] = value
            console.log('entra', pricesApi);
            var pricesAPIArray = pricesApi._embedded.prices;
            var priceReach = false;
            for(var i = 0; i < pricesAPIArray.length; i++){
                if(pricesAPIArray[i].opyProductType == value){
                    priceReach = true;
                    console.log('pumpArray', pricesAPIArray[i]);
                    vm.refueling.price = pricesAPIArray[i].posPrice;
                    priceFueltypeObject = pricesAPIArray[i];
                    break;
                }
            }
            if(!priceReach) priceFueltypeObject = {};
        }

        if(handler == "amount"){
            vm.refueling[handler] = value;
            console.log('priceFueltypeObject', priceFueltypeObject);
            
                if(typeof priceFueltypeObject.promotionPerValue !== 'undefined' &&  priceFueltypeObject.promotionPerValue !== null  && typeof priceFueltypeObject.promotionPerValue[value].discount !== 'undefined' && priceFueltypeObject.promotionPerValue[value].discount > 0){
                    vm.promotion = priceFueltypeObject.promotionPerValue[value];                            
                }                    
                console.log('vm.promotion', vm.promotion);
            
        }

    };
    
    var dragright = function (e) {
        $ionicSideMenuDelegate.canDragContent(false);

        if (!vm.loading) {
            btn.style.webkitTransform = 'translate3d(' + e.gesture.deltaX + 'px, 0px, 0px) scale(1)';
            console.log('btn.style 1', btn.style.webkitTransform);
            //console.log(e.gesture.deltaX);
            if (e.gesture.deltaX > positionBtn) {
                $scope.$apply(function () {
                    vm.loading = true;
                    vm.finish = true;
                });
                //btn.style.webkitTransform = 'translate3d(' + positionBtn + 10 + 'px, 0px, 0px) scale(1)';
                console.log('btn.style 2', btn.style.webkitTransform);
                ionic.EventController.offGesture(dragRightGesture, 'dragright');
                ionic.EventController.offGesture(dragEndGesture, 'dragend');
                dragend();
                if(vm.refueling.full) vm.refueling.amount = "99";
                delete vm.refueling.full;
                
                for (key in vm.refueling){
                    console.log('key', key);
                    vm.refueling[key] = vm.refueling[key].toString();
                }
                console.log('vm.refueling', vm.refueling);
                //call to refuel Service
                vm.callRefuelService(vm.refueling);
            };

        }
    };
    
    var dragend = function () {
        if (!vm.loading) btn.style.webkitTransform = 'translate3d(0px, 0px, 0px) scale(1)';
        console.log('btn.style 3', btn.style.webkitTransform);
        $ionicSideMenuDelegate.canDragContent(true);
    };    

    var loadStation = function () {
        if ($state.params.station) {
            //don´t have to check clossest station
            station = angular.fromJson($state.params.station);
            getStation(station.idstation, myPosition);
        } else {
            refuelService.getClosest(0, myPosition).then(function (stationApi) {
                console.log('getClosest', stationApi);               
                getStation(stationApi.idoffstation, myPosition);
            }, function (getClosestError) {
                console.log('getClosestError', getClosestError);
                 vm.refuelingLoading = false;
            });

        }
    }
    
    ionic.DomUtil.ready(function () {
        pumpDiv = $ionicScrollDelegate.$getByHandle('pump').getScrollView();
        fueltypeDiv = $ionicScrollDelegate.$getByHandle('fueltype').getScrollView();
        amountDiv = $ionicScrollDelegate.$getByHandle('amount').getScrollView();

        btnElements = document.querySelector('.btnSendContainer');
        maskWidth = btnElements.offsetWidth;
        positionBtn = (maskWidth / 2.2) - 5;
        btn = document.querySelector('.btnSend');
        btnSendContainer = document.querySelector('.btnSendContainer');

        dragRightGesture = ionic.EventController.onGesture('dragright', dragright, btn, {});
        dragEndGesture = ionic.EventController.onGesture('dragend', dragend, btn, {});

    });

    /**
     * If can get our position by plugin, init the refuel and record it in the localStore
     * @param {Object} location return of the plugin with my location
     */
    var onSuccess = function (location) {
        myPosition = location;
        /*myPosition = {
            lat: 41.53519,
            lng: 2.11067
        }*/
        //Ondeploy
        /*
        myPosition = {
            lat: 41.53519,
            lng: 2.11067
          /*
          CORUÑA
          lat: 43.36683,
          lng: -8.41342
          */
          /*
          LOWCOST
          lat: 41.53519,
          lng: 2.11067
          */
          /*
          BARCELONA
          lat: 41.448,
          lng: 2.18828
          */
          
        /*};*/
        console.log('location1', location);
        loadStation();
    };
    /**
     * if we can´t then tell the user to enable the gps
     * @param {Object} msg Error message from the google Plugin
     */
    var onError = function (msg) {
        console.log('error', msg);
        popUpopen = true;
        var button = {
            text: 'Activar GPS',
            type: 'button-positive',
            onTap: function (e) {
                myPopup.close();
                MapService.myLocation(false, true).then(onSuccess, onError);
            }
        };

        var text = "Ha ocurrido un error con tu GPS.";
        if (msg.error_code == "service_denied") text = 'Tienes que activar tu GPS para poder Respotar.';
        else if (msg.error_code == "service_not_available") {
            text = 'O no tienes, o has bloqueado la opción de GPS, por favor cierra la aplicación, activa tu GPS y vuelve a abrirla.';
            button = {
                text: 'Cerrar la aplicación',
                type: 'button-positive',
                onTap: function (e) {
                    ionic.Platform.exitApp();
                }
            };
        }

        myPopup = $ionicPopup.show({
            title: '<i class="icon ion-alert-circled openy fsBig"></i>',
            template: text,
            scope: $scope,
            buttons: [
                button
            ]
        });
        vm.refuelingLoading = false;
        updateBackButton();
    };
        
    $scope.$on('$ionicView.enter', function () {
        $ionicScrollDelegate.scrollTop();
        vm.pumps = [];
        vm.amounts = [];
        vm.fueltypes = [];
        vm.promotion = {};
        vm.finish = false;
        vm.loading = false;
        var myPopup,
            popUpopen,
            prePump = [],
            priceFueltypeObject = {},
            pricesApi = {},
            sendRefuelData = {};  
        pumpDiv = $ionicScrollDelegate.$getByHandle('pump').getScrollView();
        fueltypeDiv = $ionicScrollDelegate.$getByHandle('fueltype').getScrollView();
        amountDiv = $ionicScrollDelegate.$getByHandle('amount').getScrollView();
        $ionicScrollDelegate.$getByHandle('pump').scrollTo(0,0);
        $ionicScrollDelegate.$getByHandle('fueltype').scrollTo(0,0);
        $ionicScrollDelegate.$getByHandle('amount').scrollTo(0,0);
        console.log('$ionicView.enter vm.pumps ', vm.pumps);
        $ionicSideMenuDelegate.canDragContent(false);
        btn = document.querySelector('.btnSend');
        btn.style.webkitTransform = 'translate3d(0px, 0px, 0px) scale(1)';
        
        if (!initialized) {
            initialized = true;
            /*-deploy-*/
            /*vm.refueling = {
                "idoffstation": "9085",
                "pump":"3", 
                "fueltype":"G95",
                "amount":"30",
                "price":"1.099",
                "email": "cristojvt@gmail.com",
                "antifraudPin":"",
                "userPin":""
            };
            vm.callRefuelService(vm.refueling);
            return;*/
            /*-deploy-*/
            console.log('$ionicView.enter');
            //add check credit card
            if($rootScope.myUser !== 'undefined' && $rootScope.myUser.validated && $rootScope.myUser.validated !== 'undefined' && $rootScope.myUser.validated){
                 vm.refuelingLoading = true;
                 vm.refueling.email = $rootScope.myUser.email
                 //3º param only deploy
                 MapService.myLocation(false, true).then(onSuccess, onError);                 
            } else {                
                popUpopen = true;
                myPopup = $ionicPopup.show({
                    title: '<i class="icon ion-alert-circled openy fsBig"></i>',
                    template: 'Debes tener una cuenta validada para poder repostar',
                    scope: $scope,
                    buttons: [
                        {
                            text: 'registrarme',
                            type: 'positive',
                            onTap: function(e){
                                myPopup.close();
                                $state.go('app.login');
                            }
                        }
                    ]
                });
                updateBackButton();
            }            
        }
    });

    $rootScope.$on('$stateChangeSuccess', function (event, toState, toParams, fromState, fromParams) {
        console.log('popUpopen', popUpopen);
        if(popUpopen) {
            console.log('popUpopen');
            myPopup.close();
        }
        console.log('fromState - 1', fromState.name);
        if (fromState.name !== 'app.refuel.select' && fromState.name !== 'app.refuel.selected' && fromState.name !== 'app.refuel.pin' && fromState.name !== 'app.refuel.antifraudpin' && toState.name !== 'app.refuel.waiting' && toState.name !== 'app.refuel.completed') {
            console.log('fromState - 2', fromState.name);
            vm.refueling = {};
            initialized = false;
            
        }

    });

};

/**
 * [[Description]]
 * @param {Object}   $scope [[Description]]
 * @param {[[Type]]} $state [[Description]]
 */

var refuelPinCtrl = function refuelPinCtrl($scope, $state, $timeout, $stateParams, $ionicHistory) {
    'use estrict';
    var vm = this;
    
    $scope.refuelCtrl.refueling.userPin = '';
    vm.hiddenRefuelHeader = true;

    console.log('$scope.refuelCtrl.refueling', $scope.refuelCtrl.refueling);
    
    $scope.refuelCtrl.refueling = angular.fromJson($state.params.refueling);
    
    

    vm.onKeyPress = function (i) {
        $scope.refuelCtrl.refueling.userPin += i;        
        if ($scope.refuelCtrl.refueling.userPin.length == 4) {
            var timedisableBack = $timeout(function () {
                $ionicHistory.nextViewOptions({
                    disableBack: true
                });
                $scope.refuelCtrl.callRefuelService($scope.refuelCtrl.refueling);   
            }, 100);
        }
    };

    vm.onDeletePress = function () {
        console.log('delete pressed');
        $scope.refuelCtrl.refueling.userPin = $scope.refuelCtrl.refueling.userPin.substr(0, $scope.refuelCtrl.refueling.userPin.length - 1);
    };

    $scope.$on('$ionicView.enter', function () {
        $scope.refuelCtrl.refueling.userPin = '';
        $timeout(function () {
            var $refuelHeader = document.getElementById('refuelHeader');
            console.dir($refuelHeader);
            vm.refuelHeaderheight = $refuelHeader.offsetHeight;
        }, 300);
    });
    
    $scope.$on('$destroy', function(){
        $timeout.cancel(timedisableBack);    
    });
    

    
};


var refuelAntifraudpinCtrl = function refuelAntifraudpinCtrl($scope, $state, $stateParams, $timeout, refuelService) {
    'use estrict';
    var vm = this;
    console.log('$scope.refuelCtrl.refueling', $scope.refuelCtrl.refueling);
    console.log('$state.params.refueling', $state.params);
    $scope.refuelCtrl.refueling = angular.fromJson($state.params.refueling);
    $scope.refuelCtrl.refueling.antifraudPin = '';    
    $scope.refuelCtrl.hiddenRefuelHeader = true;
    
    vm.onKeyPress = function (i) {
        $scope.refuelCtrl.refueling.antifraudPin += i;        
        if ($scope.refuelCtrl.refueling.antifraudPin.length == 4) {
            var timerDisableBack = $timeout(function () {
                $ionicHistory.nextViewOptions({
                    disableBack: true
                });
                $scope.refuelCtrl.callRefuelService($scope.refuelCtrl.refueling);               
            }, 100);
        }
    };

    vm.onDeletePress = function () {
        console.log('delete pressed');
        $scope.refuelCtrl.refueling.antifraudPin = $scope.refuelCtrl.refueling.antifraudPin.substr(0, $scope.refuelCtrl.refueling.antifraudPin.length - 1);
    };

    $scope.$on('$ionicView.enter', function () {
        $scope.refuelCtrl.refueling.antifraudPin = '';
        var timer = $timeout(function () {
            var $refuelHeader = document.getElementById('refuelHeader');
            console.dir($refuelHeader);
            vm.refuelHeaderheight = $refuelHeader.offsetHeight;
        }, 300);
    });
    $scope.$on('$destroy', function(){
        $timeout.cancel(timer);
        $timeout.cancel(timerDisableBack);
    })

};


var refuelModalWaitCtlr = function refuelModalWaitCtlr($scope){
    var vm = this;
    vm.closeModalWait = function() {
        console.log('closeModalWaitEvent');
        $scope.$emit('closeModalWaitEvent');  
    }    
};

var refuelWaitingCtrl = function refuelWaitingCtrl($scope, $state, $interval, $ionicModal, $ionicHistory, $stateParams, $ionicPopup, $ionicPlatform) {
    'use estrict';
    var vm = this,
        modalHang,
        modalRaise,        
        myPopup; 
    
    console.log('$stateParams', $state.params);
    
    var hangModalFn = function(){
        console.log('hangModalFn');
        console.log('modalRaise', modalRaise);
        if(typeof modalRaise !== 'undefined' && typeof modalRaise.hide == 'function'){
             console.log('modalRaiseRemove');
             modalRaise.hide();
             modalRaise.remove();
        }
        var template2 = 'modules/refuel/views/refueling_wait_hangpump_modal.html';
        var hang = $ionicModal.fromTemplateUrl(template2, {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function (modal) {            
            modalHang = modal;                       
            modalHang.show();
        });
    };
    
    $scope.$on('$ionicView.beforeEnter', function () {
        
        if($state.params && $state.params.type && $state.params.type == 1){
            hangModalFn();
        }else if($state.params && $state.params.type && $state.params.type == 2) {
            if(modalHang !== undefined) modalHang.hide();
            $state.go('app.refuel.completed', {collect: $state.params.collect})
        }else if($state.params && $state.params.type && $state.params.type == 3){
            timeExceeded();
        }else{
            template = 'modules/refuel/views/refueling_wait_raisepump_modal.html';     
            var raising = $ionicModal.fromTemplateUrl(template, {
                scope: $scope,
                animation: 'slide-in-up'
            }).then(function (modal) {
                vm.lineWidth = 5;
                modalRaise = modal;
                modalRaise.show();             
            });
        }
        
        var closeModalWait = function () {
            console.log('closeModalWait fn');
            myPopup = $ionicPopup.show({
                title: '<i class="icon ion-alert-circled openy fsBig"></i>',
                template: 'Estás seguro que quieres anular el repostaje, entonces no se te cargará nada en tu cuenta, recuerda que puedes reiniciar este proceso siempre que quieras',
                scope: $scope,
                buttons: [
                    {
                        text: 'Aceptar',
                        type: 'positive',
                        onTap: function(e){
                            myPopup.close();
                            modalRaise.hide();
                            modalRaise.remove();
                            $ionicPlatform.onHardwareBackButton(function onBackKeyDown(e) {
                                e.preventDefault();
                                e.stopImmediatePropagation();
                                e.stopPropagation();
                                $state.go('app.refuel.select');
                                return false;
                            });
                            $state.go('app.refuel.select');
                        }
                    },
                    {
                        text: 'Cancelar'
                    }
                ]
            });

        };
        
        $scope.$on('closeModalWaitEvent', function(event, data){
            console.log('closeModalWait');
            closeModalWait();
        });               

        
        $ionicHistory.nextViewOptions({
            disableAnimate: false,
            disableBack: true
        });

        $scope.$on('waitComplete', function(event, data){
            console.log('waitComplete');
            if(data === true) {
                timeExceeded();
            }
        });
        
       
        $scope.$on('pushRaise', function(event, response){
            console.log('REFUEL push raise');
            hangModalFn();
        });

        $scope.$on('pushHang', function(event, response){
            console.log('REFUEL push hang', response);
            modalHang.hide();
            modalHang.remove();
            console.log('hang 1');
            $state.go('app.refuel.completed', {collect: angular.toJson(response)});
            console.log('hang 2');
        }); 
        
        $scope.$on('$destroy', function() {
            if(typeof modalRaise !== 'undefined' &&  modalRaise.remove === "function") modalRaise.remove();
            if(typeof modalHang !== 'undefined' &&  modalHang.remove === "function") modalHang.remove();
        });
        
    });
    
    var timeExceeded = function (){
        myPopup = $ionicPopup.show({
            title: '<i class="icon ion-alert-circled openy fsBig"></i>',
            template: 'Se ha superado el tiempo máximo para descolgar, esta operación ha sido cancelada, no se cargará importe alguno en su cuenta',
            scope: $scope,
            buttons: [
                {
                    text: 'Aceptar',
                    type: 'positive',
                    onTap: function(e){
                        myPopup.close();
                        if(typeof modalRaise !== 'undefined' && angular.isFunction(modalRaise.hide)){
                            modalRaise.hide();
                            modalRaise.remove();    
                        }                        
                        $ionicPlatform.onHardwareBackButton(function onBackKeyDown(e) {
                            e.preventDefault();
                            e.stopImmediatePropagation();
                            e.stopPropagation();
                            $state.go('app.refuel.select');
                            return false;
                        });
                        $state.go('app.refuel.select');
                        
                    }
                }
            ]
        });
    }
    
};


/**
 * [[Description]]
 * @param {Object}   $scope      [[Description]]
 * @param {[[Type]]} $interval   [[Description]]
 * @param {[[Type]]} $ionicModal [[Description]]
 */

var refuelCompletedCtrl = function refuelCompletedCtrl($scope, $state, $stateParams, store, refuelService) {
    'use estrict';
    console.log('refuelCompletedCtrl');
    var vm = this;
    
    $scope.$on('$ionicView.beforeEnter', function () {
        if(typeof $state.params.collect !== 'undefined'){
            if(typeof $scope.refuelCtrl.stationInfo == 'undefined') $scope.refuelCtrl.stationInfo = {};
            console.log('$state.params.collect', $state.params.collect)
            var dataCollet = angular.fromJson($state.params.collect);    
            vm.receipt = dataCollet.summary.details;
            vm.receipt.timestamp = Math.round(new Date(vm.receipt.Fecha).getTime())
            vm.receipt.receiptid = dataCollet.receiptid;
            /*vm.receipt.idorder = dataCollet.summary.data.idorder;
            vm.receipt.idoffstation = dataCollet.summary.data.idoffstation;*/
            vm.receipt.name = dataCollet.billingdata.billingName;
            vm.receipt.logo = dataCollet.billingdata.billingLogo;
            $scope.refuelCtrl.stationInfo.logoname = vm.receipt.logo;
            $scope.refuelCtrl.stationInfo.name = vm.receipt.name;
            $scope.refuelCtrl.stationInfo.address = dataCollet.billingdata.billingAddress;
            console.log('receipt', vm.receipt);
            var billStoreObject = {};
            billStoreObject.receipts = [];            
            billStoreObject.total = 1;
            if (store.get('bills')) {
                var billStoreObject = store.get('bills');                
                billStoreObject.total++;
            }
            billStoreObject.receipts.splice(0, 0, vm.receipt);
            store.set('bills', billStoreObject);    
        }
        else{
            window.plugins.toast.showLongBottom('Ha ocurrido un error no se ha procesado ningún recibo');
        }
    });
    
    
    /*
    refuelService.collect().then(function(receiptData){
        vm.receipt = receiptData;
    }, function(error){
        window.plugins.toast.showLongBottom('Ha ocurrido un error no se ha procesado ningún recibo');
        $state.go('app.stations');
    });*/
    
    vm.gotoRefuel = function(){
        $ionicHistory.nextViewOptions({disableBack: true});
        $state.go('app.refuel.select');
    }
    
    

};


angular.module('starter.refuel', [])

    .controller('refuelController', ['$scope', '$rootScope', '$state', '$ionicPopup', '$ionicPlatform', '$ionicHistory', '$ionicScrollDelegate', '$ionicSideMenuDelegate', 'store', 'stationsService', 'MapService', 'refuelService', refuelCtrl])

    .controller('refuelPinController', ['$scope', '$state', '$timeout', '$stateParams', '$ionicHistory', refuelPinCtrl])
    
    .controller('refuelAntifraudpinController', ['$scope', '$state', '$stateParams', '$timeout', 'refuelService', refuelAntifraudpinCtrl])

    .controller('refuelModalWaitController', ['$scope', refuelModalWaitCtlr])

    .controller('refuelWaitingController', ['$scope', '$state', '$interval', '$ionicModal', '$ionicHistory',  '$stateParams', '$ionicPopup', '$ionicPlatform', refuelWaitingCtrl])
    
    .controller('refuelCompletedController', ['$scope', '$state', '$stateParams', 'store', 'refuelService', refuelCompletedCtrl])

;