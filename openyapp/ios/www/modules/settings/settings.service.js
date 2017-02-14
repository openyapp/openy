angular.module('starter.services').factory('settingsService', function (store, $q, $http, CONFIG) {
    var storePreferences = function (data) {
         store.set('myInvoice', {
            inv_address : data.inv_address,
            inv_cicle : data.inv_cicle,
            inv_country : data.inv_country,
            inv_document : data.inv_document,
            inv_document_type : data.inv_document_type,
            inv_locality : data.inv_locality,
            inv_name : data.inv_name,
            inv_postal_code : data.inv_postal_code
        });
        store.set('myCreditCard', data.default_credit_card);
        store.set('myPaymentPin', data.payment_pin);
    };

    return {
        updateProfile: function (id, data){
            var deferred = $q.defer();
            $http({
                    url: CONFIG.APIURL + 'oauthuser/'+ id,
                    method:  "PATCH",
                    data : data,
                    headers: {'Content-Type': 'application/json',Accept: 'application/json'}
            })
            .success(function(data) {
                deferred.resolve(data.response);
            })
            .error(function(error) {
                console.log(error);
                if(error.detail && error.detail == 'Password not match') {
                    deferred.reject('La contraseña no coincide');
                } else if (error.response) {
                    deferred.reject(error.detail);
                } else {
                    deferred.reject('El servicio no está disponible');
                }
            });
            return deferred.promise;
        },
        recoverPassword : function (email) {
            var deferred = $q.defer();
            $http({
                    url: CONFIG.APIURL + 'recoverpassword',
                    method:  "POST",
                    data : {email: email},
                    skipAuthorization: true,//no queremos enviar el token en esta petición
                    headers: {'Content-Type': 'application/json',Accept: 'application/json'}
            })
            .success(function(data) {
                deferred.resolve(true);
            })
            .error(function(error) {
                if (error && error.detail) {
                    deferred.reject(error.detail);
                } else {
                    deferred.reject('El servicio no está disponible');
                }
            });
            return deferred.promise;
        },
        sendCodeNewPhone : function (iduser, phone) {
            var deferred = $q.defer();
            $http({
                    url: CONFIG.APIURL + 'sendcodenewphone',
                    method:  "POST",
                    data : {iduser: iduser, new_phone_number: phone},
                    headers: {'Content-Type': 'application/json',Accept: 'application/json'}
            })
            .success(function(data) {
                deferred.resolve(true);
            })
            .error(function(error) {
                if (error.data.status == 412) {
                        deferred.reject('Demasiados intentos de validación. Sólo se permiten 3 por día.');
                } else {
                    deferred.reject(error.data.detail);
                }
            });
            return deferred.promise;
        },
        verifyNewPhone : function (iduser, code) {
            var deferred = $q.defer();
            $http({
                    url: CONFIG.APIURL + 'verifysmsnewphone/' + code + '/' + iduser,
                    method:  "GET",
                    headers: {'Content-Type': 'application/json',Accept: 'application/json'}
            })
            .success(function(data) {
                deferred.resolve(true);
            })
            .error(function(error) {
                deferred.reject('No se ha podido verificar.');
            });
            return deferred.promise;
        },
        getPreferences : function (iduser) {
            var deferred = $q.defer();
            $http({
                    url: CONFIG.APIURL + 'preference/' + iduser,
                    method:  "GET",
                    headers: {'Content-Type': 'application/json',Accept: 'application/json'}
            })
            .success(function(data) {
               storePreferences(data);
                deferred.resolve(data);
            })
            .error(function(error) {
                console.log(error);
                deferred.reject(error);
            });
            return deferred.promise;
        },
        changePreferences : function (iduser, data) {
            var deferred = $q.defer();
            $http({
                    url: CONFIG.APIURL + 'preference/' + iduser,
                    method:  "PATCH",
                    data: data,
                    headers: {'Content-Type': 'application/json',Accept: 'application/json'}
            })
            .success(function(data) {
                storePreferences(data);
                deferred.resolve(data);
            })
            .error(function(error) {
                console.log(error);
                deferred.reject(error);
            });
            return deferred.promise;
        },
        getCreditCards : function (page) {
            var deferred = $q.defer();
            $http({
                url: CONFIG.APIURL + 'creditcard',
                method:  "GET",
                params: {page : page},
                headers: {'Content-Type': 'application/json',Accept: 'application/json'}
            })
            .success(function(data) {
                console.log(data);
               deferred.resolve(data);
            })
            .error(function(error) {
                deferred.reject(error.detail);
            });
            return deferred.promise;
        },
        addCreditCard : function (pan, year, month, cardusername, cvv) {
            if(typeof month == 'number') {
                month = month.toString();
            }
            if(typeof cvv == 'number') {
                cvv = cvv.toString();
            }
            var deferred = $q.defer();
            $http({
                url: CONFIG.APIURL + 'creditcard',
                method:  "POST",
                data: {pan :pan, year: year, month: month, cardusername: cardusername, cvv: cvv},
                headers: {'Content-Type': 'application/json',Accept: 'application/json'}
            })
            .success(function(data) {
                deferred.resolve({active: data.active, cardusername: data.cardusername, expires: data.expires, favorite: data.favorite, idcreditcard: data.idcreditcard, modified: data.modified, pan: data.pan, validated: data.validated});
            })
            .error(function(error) {
                if(error.status == 422) {
                    deferred.reject('Los datos que has introducido son incorrectos.');
                } else if(error.detail) {
                    deferred.reject(error.detail);
                }
            });
            return deferred.promise;
        },
        validateCreditCard: function (idcreditcard, amount) {
            var deferred = $q.defer();
            $http({
                url: CONFIG.APIURL + 'creditcardvalidation',
                method:  "POST",
                data: {idcreditcard :idcreditcard, amount: amount},
                headers: {'Content-Type': 'application/json',Accept: 'application/json'}
            })
            .success(function(data) {
                console.log(data)
                deferred.resolve(data);
            })
            .error(function(error) {
                console.log(error)
                if(error.status == 422) {
                    deferred.reject('La tarjeta no es válida.');
                } else if(error.status == 403) {
                    deferred.reject('Se ha sobrepasado el número de intentos, 3 intentos.');
                } else if(error.status == 404) {
                    deferred.reject('No se ha encontrado la tarjeta.');
                } else if(error.status == 410) {
                    deferred.reject('La tarjeta ya ha sido validada.');
                } else if(error.status == 401) {
                    deferred.reject('Importe incorrecto.');
                } else if(error.detail) {
                    deferred.reject(error.detail);
                }
            });
            return deferred.promise;

        },
        deleteCreditCard: function (id) {
             var deferred = $q.defer();
            $http({
                url: CONFIG.APIURL + 'creditcard/' + id,
                method:  "DELETE",
                headers: {'Content-Type': 'application/json',Accept: 'application/json'}
            })
            .success(function(data) {
                deferred.resolve(data);
            })
            .error(function(error) {
                console.log(error)
            });
            return deferred.promise;
        },
        patchCreditCard: function (id, data) {
            var deferred = $q.defer();
            $http({
                url: CONFIG.APIURL + 'creditcard/' + id,
                method:  "PATCH",
                data: data,
                headers: {'Content-Type': 'application/json',Accept: 'application/json'}
            })
            .success(function(data) {
                console.log(data)
                deferred.resolve(data);
            })
            .error(function(error) {
                console.log(error)
                if(error.status == 404) {
                    deferred.reject('No se ha encontrado la tarjeta.');
                } else if(error.detail) {
                    deferred.reject(error.detail);
                }
            });
            return deferred.promise;
        }
    }
});
