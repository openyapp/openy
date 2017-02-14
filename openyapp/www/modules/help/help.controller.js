var helpMenuCtrl = function helpMenuCtrl($scope, store) {
    'use estrict';
    $scope.modal = function () {
        alert('sad');
    };
};
var helpCtrl = function helpCtrl($scope, store) {
    'use estrict';
    $scope.modal = function () {
        alert('sad');
    };
};

angular.module('starter.help', [])
    .controller('helpMenuCtrl', ['$scope', 'store', helpMenuCtrl])
    .controller('helpCtrl', ['$scope', 'store', helpCtrl])
    .controller('helpDeskCtrl', ['helpService', '$rootScope', '$scope', '$state', function(helpService, $rootScope, $scope, $state) {
        var vm = this,
            subject = '',
            destiny = '';
        vm.loading = false;
        vm.email = '';
        if (typeof $rootScope.myUser !== 'undefined' && typeof $rootScope.myUser.email !== 'undefined') {
            vm.email = $rootScope.myUser.email;
        };

        vm.send= function (form) {
            if(form.$valid) {
                vm.loading = true;
                helpService.sendfeedback(vm.email, subject, vm.comment, destiny).then(function () {
                    vm.loading = false;
                    window.plugins.toast.showShortCenter('Consulta enviada');
                    vm.comment = '';
                    form.$setPristine();
                    form.$setUntouched();
                }, function (error) {
                    vm.loading = false;
                    window.plugins.toast.showShortCenter(error);
                });
            }
        };
        $scope.$on('$ionicView.enter', function () {
            vm.comment = '';
            if ($state.current.name === 'app.help.suggestions') {
                subject = 'Sugerencias';
                destiny = 'feedback@openy.es';
            } else if ($state.current.name === 'app.help.contact') {
                subject = 'Contacto';
                destiny = 'contact@openy.es';
            } else {
                subject = 'Soporte Técnico';
                destiny = 'dev@openy.es';
            }
        });

    }])
    .controller('helpIframeCtrl', ['CONFIG', '$state', '$scope', function(CONFIG, $state, $scope) {
        var vm = this;
        vm.hideLoading = function () {
            $scope.$apply(function () {
                vm.loading = false;
            });
        };
        $scope.$on('$ionicView.enter', function () {
            vm.loading = true;
            if ($state.current.name === 'app.help.faqs') {
                vm.URL= CONFIG.APIURL + 'appframe/faq';
            } else if ($state.current.name === 'app.help.privacy') {
                vm.URL= CONFIG.APIURL + 'appframe/privacy';
            } else {
                vm.URL= CONFIG.APIURL + 'appframe/terms';
            }
        });
    }]);
