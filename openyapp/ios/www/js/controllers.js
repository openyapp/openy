angular.module('starter.controllers', [])
    .controller('AppCtrl', function ($scope, authService, $ionicSideMenuDelegate, MapService, $state, $rootScope, store) {
        'use estrict';
        $scope.height = Math.round(window.innerHeight);
        $scope.width = Math.round(window.innerWidth);
        $scope.$watch(function () {
                return $ionicSideMenuDelegate.getOpenRatio();
            },
            function (ratio) {
                if (ratio == 1) MapService.setClickable(false);
                else {
                    if ($state.current.name == 'app.stations') MapService.setClickable(true);
                    else MapService.setClickable(false);
                }
            });

        $rootScope.myUser = store.get('myUser') || false;
        $rootScope.myInvoice = store.get('invoicePreference') || false;
    });
