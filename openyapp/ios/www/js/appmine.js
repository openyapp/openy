// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
var db = null;
angular.module('starter', [
    'ionic',
    'angular-jwt',
    'angular-storage',
    'starter.controllers',
    'starter.services',
    'starter.refuel',
    'starter.stations',
    'starter.share',
    'starter.bills',
    'starter.settings',
    'starter.auth',
    'starter.help',
    'dbaq.ionNumericKeyboard'
])
    .constant('CONFIG', {
        //APIURL : 'https://sandbox.trocobuy.com/api/v1/',
        APIURL: 'http:///',
    })

.run(['$ionicPlatform', 'General', 'store', 'authService', '$rootScope', '$ionicLoading', 'MapService', 'pushService', function ($ionicPlatform, General, store, authService, $rootScope, $ionicLoading, MapService, pushService) {
    var location;
    //document.addEventListener("deviceready", function() {
    $ionicPlatform.ready(function () {
        if (window.cordova && window.cordova.plugins.Keyboard) {
            cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
        }
        if (window.StatusBar) {
            // org.apache.cordova.statusbar required
            StatusBar.styleDefault();
        }
        $rootScope.login = authService.isLogin();

        //init push
        var push = PushNotification.init({
            "android": {
                "senderID": "754117127892"
            },
            "ios": {
                "alert": "true",
                "badge": "true",
                "sound": "true"
            }
        }),
            pushId = false,
            location = false;
        push.on('registration', function (data) {
            if (location) {
                store.set('registrationId', data.registrationId)
                authService.clientRegister(location.lat, location.lng, data.registrationId);
            } else {
                pushId = data.registrationId;
            }
        });
        push.on('notification', function (data) {
            // data.message,
            // data.title,
            // data.count,
            // data.sound,
            // data.image,
            // data.additionalData
            alert('notidication');
            console.log('notification', data);
            pushService.pushEvent(data);
        });
        push.on('error', function (e) {
            // e.message
            console.log('pushErrror', e);
        });        
        
    
        console.log('my location')

        MapService.myLocation(false, false).then(function (data) {
            location = data;
            if (pushId) {
                authService.clientRegister(data.lat, data.lng, pushId)
            }
        }, function () {
            if (pushId) {
                authService.clientRegister(0, 0, pushId)
            } else {
                location = {lat: 0, lng:0};
            }
        }).then(function(){
            $rootScope.$broadcast('myLocationRUN', location);
        });

        db = window.openDatabase('Openy', '1.0', 'Openy', 200000);

        var d = new Date();
        //d.setDate(d.getDate() + 2);
        var dateToday = new Date(d.getFullYear(), d.getMonth(), d.getDate());
        /*store.remove('updateStations');
        store.remove('updatePrices');
        store.remove('updateFueldType');*/
        // General.inicializeTables();
        if (!store.get('updateStations')) store.set('updateStations', 0);
        if (!store.get('updatePrices')) store.set('updatePrices', 0);
        if (!store.get('updateFueldType')) store.set('updateFueldType', 0);



        if (parseInt(store.get('updateStations')) < (dateToday.valueOf() / 1000) || store.get('updateStations') == 0) {
            $ionicLoading.show({
                template: 'Cargando datos<br>esto puede tardar hasta un minuto...'
            });
            General.updateStations();
        }
        if (parseInt(store.get('updatePrices')) < (dateToday.valueOf() / 1000) || store.get('updatePrices') == 0) {

            General.updatePrices();
        }
        if (parseInt(store.get('updateFueldType')) < (dateToday.valueOf() / 1000) || store.get('updateFueldType') == 0) {

            General.updateFueldType();
        }
        General.getFavorites();

        //General.updateStations(); //Actualizo datos de gasolineras al iniciar la app
        //General.updatePrices(); //Actualizo datos de gasolineras al iniciar la app
        //General.updateFueldType(); //Actualizo datos de gasolineras al iniciar la app

        //   var db = window.sqlitePlugin.openDatabase({name: "openy.db"});
        // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
        // for form inputs)
    });
}])

.config(['$stateProvider', '$urlRouterProvider', '$ionicConfigProvider', 'jwtInterceptorProvider', '$compileProvider', '$httpProvider', function ($stateProvider, $urlRouterProvider, $ionicConfigProvider, jwtInterceptorProvider, $compileProvider, $httpProvider) {

    jwtInterceptorProvider.tokenGetter = ['jwtHelper', '$http', 'config', 'store', '$state', '$ionicHistory', function (jwtHelper, $http, config, store, $state, $ionicHistory) {
        // Skip authentication for any requests ending in .html
        if (config.url.substr(config.url.length - 5) === '.html') {
            return null;
        }
        var idToken = localStorage.getItem('token');
        var expires_in = localStorage.getItem('expires_in');
        var time = new Date();
        time = time.getTime();
        if (!idToken || time > expires_in) {
            // This is a promise of a JWT id_token
            var refreshToken = store.get('refresh_token');
            return $http({
                url: 'http:////oauth',
                // This makes it so that this request doesn't send the JWT
                skipAuthorization: true,
                method: 'POST',
                data: {
                    grant_type: 'refresh_token',
                    client_id: store.get('privatekey'),
                    refresh_token: refreshToken
                }
            }).then(function (response) {
                var id_token = response.data.access_token;
                store.set('token', id_token);
                store.set('refresh_token', response.data.refresh_token);
                store.set('expires_in', time + response.data.expires_in * 1000);
                return id_token;
            }, function () {
                store.remove("myUser");
                store.remove("token");
                store.remove("refresh_token");
                store.remove("expires_in");
                $ionicHistory.nextViewOptions({
                    disableAnimate: true,
                    disableBack: true,
                    historyRoot: true
                });

                $state.go('app.login');
                return null;
            });
        } else {
            return idToken;
        }
      }];

    $httpProvider.interceptors.push('jwtInterceptor');
    $compileProvider.imgSrcSanitizationWhitelist(/^\s*(https?|ftp|mailto|content|file|assets-library):/);


    $ionicConfigProvider.backButton.text('');

    $stateProvider
    .state('app', {
        url: "/app",
        abstract: true,
        templateUrl: "templates/menu.html",
        controller: 'AppCtrl'
    })

    .state('app.stations', {
            url: "/stations",
            templateUrl: "modules/stations/views/map.html",
            controller: 'stationsCtrl'
        })
        .state('app.stationslist', {
            url: "/list",
            templateUrl: "modules/stations/views/lists.html",
            controller: 'stationsListCtrl'
        })
        .state('app.station', {
            url: "/:station",
            templateUrl: "modules/stations/views/station.html",
            controller: 'stationCtrl'
        })
        .state('app.refuel', {
            url: "/refuel",
            abstract: true,
            templateUrl: "modules/refuel/views/refuel.html",
            controller: 'refuelController as refuelCtrl'
        })
        .state('app.refuel.select', {
            url: "/select/:station",
            templateUrl: "modules/refuel/views/refueling_select.html"
        })
        .state('app.refuel.pin', {
            url: "/pin/:refueling",
            templateUrl: "modules/refuel/views/refueling_pin.html",
            controller: 'refuelPinController as refuelPinCtrl'
        })
        .state('app.refuel.antifraudpin', {
            url: "/antifraudpin/:refueling",
            templateUrl: "modules/refuel/views/refueling_antifraudpin.html",
            controller: 'refuelAntifraudpinController as refuelAntifraudpinCtrl'
        })
        .state('app.refuel.waiting', {
            url: "/waiting/:collect/:type",
            templateUrl: "modules/refuel/views/refueling_waiting.html",
            controller: 'refuelWaitingController as refuelWaitingPumpCtrl'
        })
        .state('app.refuel.completed', {
            url: "/completed/:collect",
            templateUrl: "modules/refuel/views/refueling_completed.html",
            controller: 'refuelCompletedController as refuelCompletedCtrl'
        })
        .state('app.share', {
            url: "/share",
            templateUrl: "modules/share/views/share.html",
            controller: 'shareCtrl'
        })
        .state('app.bills', {
            url: "/bills",
            templateUrl: "modules/bills/views/bills.html",
            controller: 'billsCtrl'
        })
        .state('app.settings', {
            url: "/settings",
            templateUrl: "modules/settings/views/settings.html",
            controller: 'settingsCtrl as sttngsCtrl'
        })
        .state('app.setting', {
            url: "/setting",
            abstract: true,
            templateUrl: "modules/settings/views/setting.html",
            controller: 'settingCtrl'
        })
        .state('app.setting.profile', {
            url: "/profile",
            templateUrl: "modules/settings/views/profile.html",
            controller: 'profileSettingsCtrl as prflSCtrl'
        })
        .state('app.setting.password', {
            url: "/password",
            templateUrl: "modules/settings/views/password.html",
            controller: 'passwordSettingsCtrl as passCtrl'
        })
        .state('app.setting.changePhone', {
            url: "/changePhone",
            templateUrl: "modules/settings/views/change_phone.html",
            controller: 'phoneSettingsCtrl as phoneCtrl'
        })
        .state('app.setting.payment', {
            url: "/payment",
            templateUrl: "modules/settings/views/payment.html",
            controller: 'paymentSettingsCtrl as paymentCtrl'
        })
        .state('app.setting.payment_send', {
            url: "/send/:payment",
            templateUrl: "modules/settings/views/payment_send.html",
            controller: 'paymentSendSettingsCtrl as  as payment'
        })
        .state('app.setting.invoice', {
            url: "/invoice",
            templateUrl: "modules/settings/views/invoice.html",
            controller: 'invoiceSettingsCtrl  as invSetCtrl'
        })
        .state('app.setting.pin', {
            url: "/pin/:edit",
            templateUrl: "modules/settings/views/pin.html",
            controller: 'pinSettingsCtrl  as pinSetCtrl'
        })
        .state('app.help_menu', {
            url: "/help_menu",
            templateUrl: "modules/help/views/help_menu.html",
            controller: 'helpMenuCtrl'
        })
        .state('app.help', {
            url: "/help",
            abstract: true,
            templateUrl: "modules/help/views/help.html",
            controller: 'helpCtrl'
        })
        .state('app.help.faqs', {
            url: "/faqs",
            templateUrl: "modules/help/views/faqs.html",
        })
        .state('app.help.help_desk', {
            url: "/help_desk",
            templateUrl: "modules/help/views/help_desk.html",
        })
        .state('app.help.suggestions', {
            url: "/help_desk",
            templateUrl: "modules/help/views/suggestions.html",
        })
        .state('app.help.contact', {
            url: "/contact",
            templateUrl: "modules/help/views/contact.html",
        })
        .state('app.help.privacy', {
            url: "/privacy",
            templateUrl: "modules/help/views/privacy.html",
        })
        .state('app.help.terms_use', {
            url: "/terms_use",
            templateUrl: "modules/help/views/terms_use.html",
        })
        .state('app.login', {
            url: "/login",
            templateUrl: "modules/auth/views/login.html",
            controller: 'loginCtrl'
        })
        .state('app.register', {
            url: "/register",
            templateUrl: "modules/auth/views/register.html",
            controller: 'registerCtrl  as rgstrCtl'
        })
        .state('app.validate', {
            url: "/validate",
            templateUrl: "modules/auth/views/validate.html",
            controller: 'validateCtrl as vldtCtl'
        })
        .state('logout', {
            controller: function ($state, $ionicHistory, authService) {
                $ionicHistory.nextViewOptions({
                    disableAnimate: false,
                    disableBack: true,
                    historyRoot: true
                });
                authService.logout();
                $state.go('app.stations');
            }
        });

    // if none of the above states are matched, use this as the fallback
    $urlRouterProvider.otherwise('/app/stations');

}]);
