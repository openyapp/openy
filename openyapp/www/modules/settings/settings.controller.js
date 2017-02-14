angular.module('starter.settings', [])
    .controller('settingsCtrl', ['$scope', 'store', '$rootScope', 'settingsService', '$timeout', '$ionicPopup', function ($scope, store, $rootScope, settingsService, $timeout, $ionicPopup) {
        'use estrict';
        var vm = this,
            timeoutId = null;
        vm.settings = {};
        vm.loading = false;

        vm.updateDistance = function () {
            if(timeoutId !== null) {
                return;
            }
            timeoutId = $timeout( function() {
                store.set('defaultDistance', vm.settings.distance);
                $timeout.cancel(timeoutId);
                timeoutId = null;
            }, 1000);
        };
        settingsService.getPreferences($rootScope.myUser.iduser).then( function (response) {
            if(!vm.settings.invoice && response.inv_document) {
                vm.myInvoice = true;
                vm.settings.invoice = true;
                store.set('invoicePreference', true);
            }

            vm.settings.creditCard = response.default_credit_card;
            if(vm.settings.creditCard) {
                $rootScope.myCard = true;
            }

            vm.settings.paymentPin = response.payment_pin;
        });
        $scope.$on('$ionicView.enter', function(){
             vm.settings.user = $rootScope.myUser.validated;

            vm.settings.invoice = store.get('invoicePreference');
            if(vm.settings.invoice) {
                $rootScope.myInvoice = true;
            }
            vm.settings.creditCard = store.get('myCreditCard') || false;
            if(vm.settings.creditCard) {
                $rootScope.myCard = true;
            }
            vm.settings.paymentPin = store.get('myPaymentPin') || false;
            vm.settings.distance = store.get('defaultDistance') || 5;
        });
        vm.deletePin = function () {
            $ionicPopup.confirm({
                title: 'Aviso',
                template: 'Se va a proceder a eliminar el pin, todas sus tarjetas dejarán de estar activas y las deberá volver a introducir. Es por su seguridad.',
                buttons: [
                    {
                        text: '<b>Eliminar</b>',
                        type: 'button-positive',
                        onTap: function(e) {
                            return true;
                        }
                    },
                    { text: 'Cancelar'}
                ]
            }).then(function (res) {
                if (res) {
                    vm.loading = false;
                    settingsService.changePreferences($rootScope.myUser.iduser, {payment_pin: null}).then(function (response) {
                        window.plugins.toast.showShortCenter('Pin eliminado');
                        vm.settings.paymentPin= false;
                        vm.settings.creditCard= false;
                        $rootScope.myCard = false;
                        vm.loading = false;
                    }, function (error) {
                        window.plugins.toast.showShortCenter(error);
                        vm.loading = false;
                    });
                }
            });
        };
    }])
    .controller('settingCtrl', ['$scope', '$state', function ($scope, $state, $stateParams) {
        'use estrict';
        console.log('stateParams', $stateParams);
    }])
    .controller('profileSettingsCtrl', ['$scope', '$ionicScrollDelegate', '$rootScope', 'store', 'settingsService', '$ionicPopup', 'authService', '$state', function ($scope, $ionicScrollDelegate, $rootScope, store, settingsService, $ionicPopup, authService, $state) {
        'use estrict';
        var vm = this;
        vm.profileData = {
            first_name: $rootScope.myUser.first_name,
            last_name: $rootScope.myUser.last_name,
            email: $rootScope.myUser.email,
            phone_number: $rootScope.myUser.phone_number
        };

        vm.update = function (field) {
            if (vm.profileData[field]) {
                var data = {},
                    old_value = '';
                if (field === 'username') {
                    old_value = $rootScope.myUser.email;
                } else {
                    old_value = $rootScope.myUser[field];
                }
                if (vm.profileData[field] !== old_value) {
                    data[field] = vm.profileData[field];
                    settingsService.updateProfile($rootScope.myUser.iduser, data).then(function (response) {
                        console.log(response);
                    }, function (error) {
                        console.log(error);
                    });
                }
            }
        };
        vm.forgotPassword = function () {
            // A confirm dialog
            $ionicPopup.confirm({
                title: 'Aviso',
                template: 'Se te enviará una nueva contraseña a tu email. Por tu seguridad te recomendamos que la cambies inmediatamente.',
                scope: $scope,
                buttons: [
                    {
                        text: '<b>Enviar</b>',
                        type: 'button-positive',
                        onTap: function(e) {
                            return true;
                        }
                    },
                    { text: 'Cancelar'}
                ]
            }).then(function (res) {
                if (res) {
                    settingsService.recoverPassword($rootScope.myUser.email).then(function () {
                        authService.removeStore();
                        $state.go('app.login');
                    }, function (error) {
                        window.plugins.toast.showShortCenter(error);
                    });
                }
            });

        };
    }])
    .controller('passwordSettingsCtrl', ['settingsService', '$ionicHistory', '$rootScope', function (settingsService, $ionicHistory, $rootScope) {
        'use estrict';
        var vm = this;
        vm.password = {};
        vm.loading = false;
        vm.changePassword = function (form) {
            if (vm.password.new !== vm.password.repeat) {
                form.password_repeat.$setValidity("matches", false);
            } else {
                form.password_repeat.$setValidity("matches", true);
            }
            if (form.$valid) {
                vm.loading = true;
                cordova.plugins.Keyboard.close();
                settingsService.updateProfile($rootScope.myUser.iduser, {
                    password: vm.password.old,
                    newpassword: vm.password.new
                }).then(function (response) {
                    window.plugins.toast.showShortCenter('Contraseña cambiada');
                    vm.loading = false;
                    $ionicHistory.goBack();
                }, function (error) {
                    window.plugins.toast.showShortCenter(error);
                    vm.loading = false;
                });
            }
        };
        vm.checkMatches = function () {
            if (vm.password.new !== vm.password.repeat) {
                form.password_repeat.$setValidity("matches", false);
            } else {
                form.password_repeat.$setValidity("matches", true);
            }
        };
    }])
    .controller('phoneSettingsCtrl', ['settingsService', '$ionicHistory', '$rootScope', 'store', function (settingsService, $ionicHistory, $rootScope, store) {
        'use estrict';
        var vm = this;
        vm.phone = {};
        vm.loading = false;
        vm.sendForm = true;
        vm.changePhone = function (form) {
            if (form.$valid) {
                vm.loading = true;
                cordova.plugins.Keyboard.close();
                settingsService.sendCodeNewPhone($rootScope.myUser.iduser, vm.phone.number).then(function (response) {
                    vm.sendForm = false;
                    vm.loading = false;
                }, function (error) {
                    window.plugins.toast.showShortCenter(error);
                    vm.loading = false;
                });
            }
        };
        vm.sendCode = function (form) {
            if (form.$valid) {
                vm.loading = true;
                cordova.plugins.Keyboard.close();
                settingsService.verifyNewPhone($rootScope.myUser.iduser, vm.phone.code).then(function (response) {
                    $rootScope.myUser.phone_number = vm.phone.number;
                    var user = store.get('myUser');
                    user.phone_number = vm.phone.number;
                    store.set('myUser', user);
                    window.plugins.toast.showShortCenter('Teléfono validado');
                    vm.loading = false;
                    $ionicHistory.goBack();
                }, function (error) {
                    window.plugins.toast.showShortCenter(error);
                    vm.loading = false;
                });
            }
        };
        vm.back = function () {
            $ionicHistory.goBack();
        };
    }])
    .controller('paymentSettingsCtrl', ['$scope', '$state', 'settingsService', '$rootScope', '$ionicScrollDelegate', function paymentSettingsCtrl($scope, $state, settingsService, $rootScope, $ionicScrollDelegate) {
        'use estrict';
        var vm = this,
            getCreditCards,
            todayDate,
            todayYear,
            creditCards = [],
            i;

        vm.page = 1;
        vm.years = [];
        vm.months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        vm.creditCards  = [];
        vm.totalCards  = 0;
        vm.showForm = false;

        getCreditCards = function () {
            settingsService.getCreditCards(vm.page).then(function (response) {
                console.log('responsefsda',response);
                if(response['_embedded'] && response['_embedded'].creditcard) {
                    vm.creditCards = response['_embedded'].creditcard;
                }

                vm.totalCards = response.total_items;
                if (!vm.totalCards) {
                    vm.setShowForm();
                }
            }, function (error) {
                console.log(error);
            });
        };

        getCreditCards();

        todayDate = new Date();
        todayYear = parseInt(('0' + todayDate.getFullYear()).slice(-2));

        for (i = todayYear; i < todayYear + 10; i++) {
            vm.years.push(('0' + i).substr(-2));
        }
        vm.card = {};
        vm.sendPaymentDataForm = function (form) {
            if (form.$valid && vm.card.year != '' && vm.card.moth != '') {
                vm.loading = true;
                settingsService.addCreditCard(vm.card.number.toString(), vm.card.year, vm.card.month.toString(), vm.card.name, vm.card.cvv).then(function (response) {
                    console.log(response);
                    vm.creditCards.push(response);
                    vm.totalCards = vm.totalCards + 1;
                    vm.loading = false;
                    vm.showForm = false;
                    vm.card = {};
                    $state.go('app.setting.payment_send', {
                        "payment": angular.toJson(response)
                    })
                }, function (error) {
                    window.plugins.toast.showShortCenter(error);
                    vm.loading = false;
                });
                /*;*/
            }
        };
        vm.deleteCard = function (id, index) {
            var card = vm.creditCards[index];
            settingsService.deleteCreditCard(id).then(function (){
                // Update preferences
                if (card.favorite) {
                    settingsService.getPreferences($rootScope.myUser.iduser);
                }
                vm.totalCards = vm.totalCards - 1;
                if (!vm.totalCards) {
                    vm.setShowForm();
                }
            },function () {
                window.plugins.toast.showShortCenter('No se ha podido borrar');
                vm.creditCards.push(card);
            });
            vm.creditCards.splice(index,1);
        };
        vm.validateCard = function (card) {
            $state.go('app.setting.payment_send', {
                "payment": angular.toJson(card)
            })
        };
        vm.addFavorite = function (id, index) {
            vm.creditCards[index].favorite = true;
            settingsService.patchCreditCard(id, {favorite: true}).then(function (){
                getCreditCards();

            },function (error) {
                window.plugins.toast.showShortCenter(error);
            });
        };
        vm.setShowForm = function () {
            vm.showForm = true;
            $ionicScrollDelegate.scrollTop();
        };
        $rootScope.$on('updateCards', function () {
            getCreditCards();
        });

    }])
    .controller('paymentSendSettingsCtrl', ['$scope', '$state', 'settingsService', '$rootScope', '$ionicHistory', function ($scope, $state, settingsService, $rootScope, $ionicHistory) {
        var vm = this;
        vm.card = angular.fromJson($state.params.payment);
        console.log(vm.card);
        vm.sendChargeChange = function () {
            $scope.sendCharge = true;
        };
        vm.validateCreditCard = function (amount) {
            amount = Math.round(amount * 100) / 100;
            vm.loading = true;
            settingsService.validateCreditCard(vm.card.idcreditcard, amount).then(function (response) {
                $rootScope.$broadcast('updateCards');
                vm.loading = false;
                $ionicHistory.goBack();
            }, function (error) {
                window.plugins.toast.showShortCenter(error);
                vm.loading = false;
            });
        };
        vm.back = function () {
            $ionicHistory.goBack();
        };
    }])
    .controller('invoiceSettingsCtrl', ['$scope', '$ionicScrollDelegate', 'store', 'settingsService', '$rootScope', function ($scope, $ionicScrollDelegate, store, settingsService, $rootScope) {
        'use estrict';
        var vm = this;
        vm.loading = false;
        vm.invoiceData = store.get('myInvoice') || {};
        vm.sendInvoiceDataForm = function (form) {
            if (form.$valid) {
                vm.loading = true;
                $ionicScrollDelegate.scrollTop();
                settingsService.changePreferences($rootScope.myUser.iduser, vm.invoiceData).then(function(response) {
                    window.plugins.toast.showShortCenter('Datos actualizados');
                    vm.loading = false;
                    store.set('invoicePreference', true);
                    $rootScope.myInvoice = true;
                }, function (error) {
                    window.plugins.toast.showShortCenter(error);
                    vm.loading = false;
                });
            }
        };
    }])
    .controller('pinSettingsCtrl', ['settingsService', '$ionicHistory', '$rootScope', '$stateParams', 'store', '$ionicPopup', function (settingsService, $ionicHistory, $rootScope, $stateParams, store, $ionicPopup) {
        'use estrict';
        var vm = this,
            pinStore = store.get('myPaymentPin') || false,
            changePin;
        vm.pin = '';
        vm.pin_repeat = '';
        vm.pin_new = '';
        vm.loading = false;
        vm.new = true;
        vm.edit = false;
        vm.delete = false;
        if (pinStore) {
            vm.new = false;
            if($stateParams.edit) {
                vm.edit = true;
            } else {
                vm.delete = true;
            }
        }
        changePin = function (form) {
            vm.loading = true;
            var pin;
            if (vm.new) {
                pin = vm.pin;
            } else if (vm.edit) {
                pin = vm.pin_new;
            } else {
                pin = null;
            }
            settingsService.changePreferences($rootScope.myUser.iduser, {payment_pin: pin}).then(function (response) {
                if (vm.delete) {
                    window.plugins.toast.showShortCenter('Pin eliminado');
                } else {
                    window.plugins.toast.showShortCenter('Pin cambiado');
                }
                vm.loading = false;
                $ionicHistory.goBack();
                form.$setPristine();
                form.$setUntouched();
            }, function (error) {
                window.plugins.toast.showShortCenter(error);
                vm.loading = false;
            });
        };

        vm.savePin = function (form) {
            if (form.$valid) {
                if (vm.delete) {
                    $ionicPopup.confirm({
                        title: 'Aviso',
                        template: 'Se va a proceder a eliminar el pin, todas sus tarjetas dejarán de estar activas y las deberá volver a introducir. Es por su seguridad.',
                        buttons: [
                            {
                                text: '<b>Eliminar</b>',
                                type: 'button-positive',
                                onTap: function(e) {
                                    return true;
                                }
                            },
                            { text: 'Cancelar'}
                        ]
                    }).then(function (res) {
                        if (res) {
                            changePin(form);
                        }
                    });

                } else {
                    changePin(form);
                }
            }
        };
        vm.checkMatchesPin = function (form) {
            if(vm.new) {
                return true;
            }
            if (vm.pin != pinStore) {
                form.pin.$setValidity("matches", false);
            } else {
                form.pin.$setValidity("matches", true);
            }
        };
        vm.checkMatches = function (form) {
            if(vm.new) {
                if (vm.pin !== vm.pin_repeat) {
                form.pin_repeat.$setValidity("matches", false);
                } else {
                    form.pin_repeat.$setValidity("matches", true);
                }
            } else {
                if (vm.pin_new !== vm.pin_repeat) {
                    form.pin_repeat.$setValidity("matches", false);
                } else {
                    form.pin_repeat.$setValidity("matches", true);
                }
            }

        };
    }]);
