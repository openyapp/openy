angular.module('starter.auth', [])
    .controller('loginCtrl', ['$scope', 'authService', '$state', '$ionicHistory', '$rootScope', 'store', function ($scope, authService, $state, $ionicHistory, $rootScope, store) {
        'use strict';
        $scope.data = {};
        $scope.login = function (form) {
            if (form.$valid) {
                $scope.loading = true;
                authService.login(form.email.$modelValue, form.password.$modelValue).then(function (res) {
                    store.set('email', form.email.$modelValue);
                    store.set('keepSession', $scope.data.keepSession);
                    $scope.data = {};
                    form.$setPristine();
                    form.$setUntouched();
                    $scope.loading = false;
                    $ionicHistory.nextViewOptions({
                        disableAnimate: false,
                        disableBack: true,
                        historyRoot: true
                    });
                    $rootScope.myUser = store.get('myUser');
                    console.log($rootScope.myUser);
                    if (res === 1) {
                        $state.go('app.validate');
                    } else {
                        $state.go('app.stations');
                    }
                }, function (error) {
                    window.plugins.toast.showShortCenter(error);
                    $scope.loading = false;
                });
            } else {
                if (form.email.$invalid) {
                    document.querySelector('input[name = "email"]').focus();
                } else if (form.password.$invalid) {
                    document.querySelector('input[name = "password"]').focus();
                }
            }
        };
        $scope.$on('$ionicView.enter', function () {
            $scope.data.email = store.get('email') || '';
            $scope.data.keepSession = true;
        });
    }])
    .controller('registerCtrl', ['$scope', '$rootScope', 'authService', '$state', 'store', '$ionicHistory', function ($scope, $rootScope, authService, $state, store, $ionicHistory) {
        'use strict';
        var self = this,
            validated = false,
            password;
        $scope.data = {};
        self.register = function (form) {
            if (form.$valid) {
                if (validated) {
                    var userAcess = store.get('userAccess');
                    password = userAcess.password;
                } else {
                    password = form.password.$modelValue;
                }
                authService.register(form.email.$modelValue, password, form.first_name.$modelValue, form.last_name.$modelValue, form.phone.$modelValue).then(function (res) {
                    store.set('keepSession', $scope.data.keepSession);
                    $scope.data = {};
                    form.$setPristine();
                    form.$setUntouched();
                    self.loading = false;
                    $ionicHistory.nextViewOptions({
                        disableAnimate: false,
                        disableBack: true
                    });
                    $rootScope.myUser = store.get('myUser');
                    $state.go('app.validate');
                }, function (error) {
                    window.plugins.toast.showShortCenter(error);
                    self.loading = false;
                });
                self.loading = true;
            } else {
                if (form.email.$invalid) {
                    document.querySelector('input[name = "email"]').focus();
                } else if (form.password.$invalid) {
                    document.querySelector('input[name = "password"]').focus();
                } else if (form.first_name.$invalid) {
                    document.querySelector('input[name = "first_name"]').focus();
                } else if (form.last_name.$invalid) {
                    document.querySelector('input[name = "last_name"]').focus();
                } else if (form.phone.$invalid) {
                    document.querySelector('input[name = "phone"]').focus();
                }
            }
        };
        $scope.$on('$ionicView.beforeEnter', function () {
            var user = store.get('myUser');
            if (user) {
                if (user.validated) {
                    $ionicHistory.nextViewOptions({
                        disableAnimate: false,
                        disableBack: true
                    });
                    $state.go('app.validate');
                } else {
                    validated = true;
                    $scope.data.email = user.email;
                    $scope.data.first_name = user.first_name;
                    $scope.data.last_name = user.last_name;
                    $scope.data.phone = user.phone_number.substr(2);
                }
            }
            $scope.data.keepSession = true;
        });
    }])
    .controller('validateCtrl', ['store', 'authService', '$state', '$ionicHistory', '$ionicPopup', function (store, authService, $state, $ionicHistory, $ionicPopup) {
        'use strict';
        var user = store.get('myUser'),
            self = this;
        if (user.validated) {
            $ionicHistory.nextViewOptions({
                disableBack: true
            });
            $state.go('app.settings');
        }
        self.sendCode = function () {
            self.loading = true;
            authService.sendCode(user.iduser).then(function () {
                window.plugins.toast.showShortCenter('Código enviado');
                self.loading = false;
            }, function (error) {
                self.loading = false;
                $ionicPopup.show({
                    template: error,
                    title: 'Aviso',
                    buttons: [
                        {
                            text: 'Aceptar'
                        },
                        {
                            text: 'Contactar',
                            onTap: function (e) {
                                $state.go('app.help.contact');
                            }
                        }
                    ]
                });
            });
        };
        self.validate = function (code) {
            self.loading = true;
            authService.validateSMS(code).then(function (result) {
                if (result) {
                    var access = store.get('userAccess') || false;
                    if (access){
                        authService.login(access.email, access.password).then(function (res) {
                            self.loading = false;
                            $ionicHistory.nextViewOptions({
                                disableAnimate: false,
                                disableBack: true,
                                historyRoot: true
                            });
                            $rootScope.myUser = store.get('myUser');
                            $state.go('app.settings');
                        }, function (error) {
                            self.loading = false;
                            $ionicHistory.nextViewOptions({
                                disableBack: true
                            });
                            $state.go('app.login');
                        });
                    } else {
                        $ionicHistory.nextViewOptions({
                            disableBack: true
                        });
                        $state.go('app.settings');
                    }
                } else {
                    self.loading = false;
                    window.plugins.toast.showShortCenter('El código es incorrecto');
                }
            }, function (error) {
                self.loading = false;
                window.plugins.toast.showShortCenter(error);
            });
        };
        self.phone = user.phone_number;
    }]);
