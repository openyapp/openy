angular.module('starter.services').factory('authService', function (store, $q, $http, CONFIG, $ionicHistory, settingsService) {
    "use strict";
    var self = this,
        registering = false,
        pushId = false,
        location = false,
        getUserInfo = function () {
            var deferred = $q.defer(),
                user = store.get("myUser");
            $http({
                method: 'GET',
                url: CONFIG.APIURL + 'oauthuser/userinfo',
                cache: false,
                skipAuthorization: false, //no queremos enviar el token en esta petición
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json'
                }
            }).then(function (res) {
                if (res.status === 200) {
                    var user = {
                        email: res.data.username,
                        first_name: res.data.first_name,
                        iduser: res.data.iduser,
                        last_name: res.data.last_name,
                        phone_number: res.data.phone_number,
                        validated: true
                    };
                    store.set("myUser", user);
                    //store.set("token", res.data.token);
                    deferred.resolve(user);
                    //     self.sendCode(res.data.iduser);
                } else {
                    deferred.reject('Se ha producido un error');
                }
            }, function (error) {
                deferred.reject(error.data.detail);
            });
            return deferred.promise;
        },
        clientRegister = function (lat, lng, idandroid) {
            if (!store.get('publickey') && !registering) {
                registering = true;
                console.log('not register')
                $http({
                    method: 'POST',
                    skipAuthorization: true, //no queremos enviar el token en esta petición
                    url: CONFIG.APIURL + 'clientregister',
                    data: {
                        osversion: device.model + device.version,
                        lat: lat,
                        lng: lng,
                        registerid: idandroid
                    },
                    cache: false,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then(function (res) {
                    console.log(res);
                    if (res.status === 201) {
                        store.set('publickey', res.data.publickey);
                        store.set('privatekey', res.data.privatekey);
                    }
                });
            } else {
                console.log('yet register');
            }
        },
        removeStore = function () {
            store.remove("myUser");
            store.remove("token");
            store.remove("refresh_token");
            store.remove("expires_in");
            store.remove("myInvoice");
            store.remove("myCreditCard");
            store.remove("myPaymentPin");
        };

    return {
        isLogin: function () {
            var token = store.get('token');
            if (token) {
                return true;
            }
            return false;
        },
        clientRegisterPush: function (id) {
            pushId = id;
            if (location) {
                clientRegister(location.lat, location.lng, id);
            }
        },
        clientRegisterLocation: function (lat, lng) {
            location = {lat: lat, lng: lng};
            if (pushId) {
                clientRegister(location.lat, location.lng, pushId);
            }
        },
        clientRegister: clientRegister,
        register: function (email, password, first_name, last_name, phone_number) {
            var deferred,
                self = this,
                deferred = $q.defer();
            $http({
                method: 'POST',
                skipAuthorization: true, //no queremos enviar el token en esta petición
                url: CONFIG.APIURL + 'register',
                data: {
                    email: email,
                    password: password,
                    first_name: first_name,
                    last_name: last_name,
                    phone_number: phone_number
                },
                cache: false,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept-Type': 'application/json'
                }
            }).then(function (res) {
                console.log(res);
                if (res.status === 201) {
                    var user = {
                        email: res.data.email,
                        first_name: res.data.first_name,
                        iduser: res.data.iduser,
                        last_name: res.data.last_name,
                        phone_number: res.data.phone_number,
                        validated: false
                    };
                    store.set("myUser", user);
                    store.set('userAccess',{email: email, password: password});
                    deferred.resolve();
                    //     self.sendCode(res.data.iduser);
                } else {
                    deferred.reject('Se ha producido un error');
                }
            }, function (error) {
                console.log(error);
                if (typeof error.data !== 'undefined' && typeof error.data.user !== 'undefined') {
                    var user = {
                        email: error.data.user.email,
                        first_name: error.data.user.first_name,
                        iduser: error.data.user.iduser,
                        last_name: error.data.user.last_name,
                        phone_number: error.data.user.phone_number,
                        validated: false
                    };
                    store.set("myUser", user);
                    store.set('userAccess',{email: email, password: password});
                    deferred.resolve();
                } else if (typeof error.data !== 'undefined' && typeof error.data.detail !== 'undefined') {
                    if(error.data.detail  == 'User already registered') {
                        deferred.reject('Ese usuario ya se encuentra registrado');
                    } else {
                        deferred.reject(error.data.detail);
                    }
                } else {
                    deferred.reject(error.statusText);
                }
            });
            return deferred.promise;
        },
        login: function (email, password) {
            var deferred,
                expired = new Date();
            expired = expired.getTime();
            deferred = $q.defer();

            $http({
                method: 'POST',
                skipAuthorization: true, //no queremos enviar el token en esta petición
                url: CONFIG.APIURL + 'oauth',
                data: {
                    username: email,
                    password: password,
                    grant_type: "password",
                    client_id: store.get('privatekey')
                },
                cache: false,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept-Type': 'application/json'
                }
            }).then(function (res) {
                console.log(res);
                if (res.status === 201 || res.status === 200) {
                    store.set("token", res.data.access_token);
                    store.set("refresh_token", res.data.refresh_token);
                    store.set("expires_in", expired + res.data.expires_in * 1000);
                    getUserInfo().then(function (response) {
                        deferred.resolve(0);
                        settingsService.getPreferences(response.iduser);
                    }, function (error) {
                        deferred.reject(error);
                    });
                } else {
                    deferred.reject(res.data.detail);
                }
            }, function (error) {
                console.log(error);
                if (error.status === 401) {
                    deferred.reject('Usuario o contraseña incorrectos');
                } else if (error.status === 409 || (error.status === 400 && error.data.user)) {
                    var user = {
                        email: error.data.user.email,
                        first_name: error.data.user.first_name,
                        iduser: error.data.user.iduser,
                        last_name: error.data.user.last_name,
                        phone_number: error.data.user.phone_number,
                        validated: false
                    };
                    store.set("myUser", user);
                    store.set('userAccess',{email: email, password: password});
                    //store.set("token", error.data.user.token);
                    deferred.resolve(1);
                } else if (error.status === 429) {
                    deferref.reject('No te hemos podido validar y has agotado el número de intentos. Por favor ponte en contacto con nosotros para continuar.');
                } else {
                    deferred.reject(error.data.detail);
                }
            });
            return deferred.promise;
        },
        removeStore: removeStore,
        logout: function () {
            store.remove("myUser");
            $ionicHistory.clearHistory()
            $ionicHistory.clearCache()
            if(store.get('refresh_token')) {
                $http({
                    method: 'GET',
                    url: CONFIG.APIURL + 'revoke',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept-Type': 'application/json'
                    }
                }).then(function (res) {
                    console.log(res);
                    removeStore();
                }, function () {
                    removeStore();
                });
            } else {
                removeStore();
            }

        },
        sendCode: function (id) {
            var deferred,
                //  console.log(device);
                deferred = $q.defer();
            $http({
                method: 'POST',
                url: CONFIG.APIURL + 'sendcode',
                data: {
                    iduser: id
                },
                cache: false,
                skipAuthorization: true, //no queremos enviar el token en esta petición
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json'
                }
            }).then(function (res) {
                if (res.status === 200) {
                    if (typeof res.data.result !== 'undefined' && res.data.result) {
                        deferred.resolve();
                    } else {
                        deferred.reject('No se ha podido enviar el SMS');
                    }
                } else {
                    deferred.reject('Se ha producido un error');
                }
            }, function (error) {
                if (error.data.status == 412) {
                    deferred.reject('No te hemos podido validar y has agotado el número de intentos. Por favor ponte en contacto con nosotros para continuar.');
                } else {
                    deferred.reject(error.data.detail);
                }
            });
            return deferred.promise;
        },
        validateSMS: function (code) {
            var deferred = $q.defer(),
                user = store.get("myUser");
            $http({
                method: 'GET',
                url: CONFIG.APIURL + 'verifysms/' + code + '/' + user.iduser,
                cache: false,
                skipAuthorization: true, //no queremos enviar el token en esta petición
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json'
                }
            }).then(function (res) {
                console.log(res);
                if (typeof res.data.result !== 'undefined') {
                    if (res.data.result) {
                        user.validate = true;
                        store.set("myUser", user);
                    }
                    deferred.resolve(res.data.result);
                } else {
                    deferred.reject(res.data.detail);
                }
            }, function (error) {
                deferred.reject('No se ha podido verificar.');
            });
            return deferred.promise;
        },
        getUserInfo: getUserInfo

    }
});
