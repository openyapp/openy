var shareCtrl = function shareCtrl ($scope) {
    'use estrict';
     var shareAvailablesAppsExpect = [
            {id:'com.facebook.katana', name:'facebook', class: 'shareFacebook', icon:'icon ion-social-facebook'},
            {id:'com.twitter.android', name: 'twitter', class: 'shareTwitter', icon:'ion-social-twitter'},
            {id:'com.whatsapp', name:'whatsapp', class: 'shareWhatsapp', icon:'ion-social-whatsapp'},
            {id:'com.google.android.apps.plus', name:'google+', class: 'shareGooglePlus', icon:'ion-social-googleplus'},
            {id:'com.skype.raider', name:'skype', class: 'shareSkype', icon:'ion-social-skype'},
            {id:'com.foursquare', name:'openy', class: 'shareFoursquare', icon:'ion-social-foursquare'}
    ],
    messageShare = 'Te recomiendo esta app! Openy.\nRepuesta en tus gasolineras favoritas con tu móvil.\nMás rápido, mejores descuentos, a todas horas.\nhttp://www.openy.es';
    if(ionic.Platform.isAndroid()){
        var errorCallback = function(result){
            $scope.shareAvailablesApps = [];
            for(var i = 0; i < shareAvailablesAppsExpect.length; i++){
                if(result.indexOf(shareAvailablesAppsExpect[i].id) > 0) $scope.shareAvailablesApps.push(shareAvailablesAppsExpect[i]);
            }

        }
        window.plugins.socialsharing.canShareVia(null, null, null, null, null, null, errorCallback);
    } else if(ionic.Platform.isIOS()){
        var errorCallback = function(result){
            $scope.shareAvailablesApps = [];
            for(var i = 0; i < shareAvailablesAppsExpect.length; i++){
                $scope.shareAvailablesApps.push(shareAvailablesAppsExpect[i]);
            }

        }
        window.plugins.socialsharing.canShareVia(null, null, null, null, null, null, errorCallback);
    }
    $scope.share = function(via){
        window.plugins.socialsharing.shareVia(via, messageShare);
    }
    var onSuccess = function(){

    };
    var onError = function(){

    };
    $scope.shareMail = function(){
        window.plugins.socialsharing.shareViaEmail(
          messageShare, // can contain HTML tags, but support on Android is rather limited:  http://stackoverflow.com/questions/15136480/how-to-send-html-content-with-image-through-android-default-email-client
          'Tienes que ver esta app',
          null, // TO: must be null or an array
          null, // CC: must be null or an array
          null, // BCC: must be null or an array
          null, // FILES: can be null, a string, or an array
          onSuccess, // called when sharing worked, but also when the user cancelled sharing via email (I've found no way to detect the difference)
          onError // called when sh*t hits the fan
        );
    };
};


angular.module('starter.share', [])
    .controller('shareCtrl',['$scope', shareCtrl])
