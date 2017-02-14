angular.module('starter.services').factory('helpService', function (store, $q, $http, CONFIG) {
    return {
        sendfeedback: function (email, subject, body, destiny) {
            var deferred = $q.defer();
            $http({
                url: CONFIG.APIURL + 'sendfeedback',
                skipAuthorization: true, //no queremos enviar el token en esta petición
                method:  "POST",
                data :   {to: destiny,from: email, subject: subject, body: body}
            })
            .success(function(data) {
                console.log(data);
                deferred.resolve(data.response);
            })
            .error(function(error) {
                if (error.response) {
                    deferred.reject(error.response);
                } else {
                    deferred.reject('El servicio no está disponible');
                }
            });
            return deferred.promise;
        }
   };

});
