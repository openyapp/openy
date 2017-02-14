var billsCtrl = function billsCtrl($scope, $rootScope, $state, $ionicSlideBoxDelegate, $ionicPopup, store, billsService, $ionicHistory, $timeout) {
    'use estrict';
    var vm = this,
        billsLocal = [];
    vm.$indexSlide = 0;    
    vm.bills = [];
    vm.page = 1;
    vm.loadingReceipt = false;
    vm.loadingInvoice = false;
    vm.loading = false;
    
    var successShare = function(){
        $scope.$apply(function(){
            vm.loadingReceipt = false;
            vm.loadingInvoice = false;    
        });
    };
    var errorShare = function(){
        window.plugins.toast.showLongBottom('Ha ocurrido un error');
        $scope.$apply(function(){
            vm.loadingReceipt = false;
            vm.loadingInvoice = false;    
        });
    };
    vm.sendReceipt = function(){
        vm.loadingReceipt = true;
        window.plugins.socialsharing.shareViaEmail(
          'Déscargate tu recibo (nº: '+vm.bills.receipts[vm.$indexSlide].receiptid+')',
          'Openy te anvía tu recibo del repostaje',
          null,
          null,
          null,
          ['http://www.dipucuenca.es/bop/boletines/2008/3/14/archivos/recibo.pdf'],
          successShare,
          errorShare
        );
    };
    
    vm.sendInvoice = function(){ 
        vm.loadingInvoice = true;
        /*
        billsService.getInvoice(vm.bolls[$indexSlide].receiptid).then(function(invoice){
            window.plugins.socialsharing.shareViaEmail(
                'Déscargate tu factura (nº: '+invoice.id+')',
                'Openy te anvía tu factura del repostaje',
                null,
                null,
                null,
                ['http://www.dipucuenca.es/bop/boletines/2008/3/14/archivos/recibo.pdf'],
                successShare,
                errorShare
            );
        }, function(error){
            errorShare();
        });
        */
        window.plugins.socialsharing.shareViaEmail(
            'Déscargate tu factura',
            'Openy te anvía tu factura del repostaje',
            null,
            null,
            null,
            ['http://www.dipucuenca.es/bop/boletines/2008/3/14/archivos/recibo.pdf'],
            successShare,
            errorShare
        );
    };
    
    var updateView = function(arrayOrder){
        if(arrayOrder == 'next') {            
            vm.bills.receipts = billsLocal.slice((vm.page-1)*10, vm.page*10);
            $timeout(function(){
               $ionicSlideBoxDelegate.slide(0);  
            });
        }
        else{
            vm.bills.receipts = billsLocal.slice((vm.page-1)*10, vm.page*10);
            $timeout(function(){
               $ionicSlideBoxDelegate.slide(9);
            });
        }
    }
    
    vm.billChanged = function (index) {
        if(vm.loadingReceipt == false || vm.loadingInvoice == false || vm.loading == false) return;
        vm.$indexSlide = index;        
        $ionicSlideBoxDelegate.update();        
    };

    vm.changeBill = function (arrayOrder) {
        console.log('changeBillOrder', arrayOrder);        
        if(arrayOrder == 'next'){
            vm.page++;
            if(billsLocal.length < vm.bills.total){
                return getBillsService(vm.page);
            }
        }else{
            vm.page--;
        }
        updateView(arrayOrder);            
    }
        
    var getBillsService = function(page){ 
        vm.loading = true;
        console.log('billsApi1', billsLocal);
        billsService.get(page).then(function(bills){            
            console.log('getBillsService', bills);
            if(angular.isArray(bills.receipts) && bills.receipts.length > 0 ){                
                
                if(page == 1) vm.bills = bills;                
                billsLocal = billsLocal.concat(bills.receipts);                
                updateView('next');
                console.log('billsApi', billsLocal);
                store.set('bills', {receipts: billsLocal, total: bills.total});                
            } else {
                console.log('billsError');    
            }
        }, function(error){
            console.log('billsError');
        }).then(function(){
            vm.loading = false;
        });
    };
    

    $scope.$on('$ionicView.enter', function () {
        vm.loading = true;
        if($rootScope.myUser !== 'undefined' && $rootScope.myUser.validated){            
            if (store.get('bills')) {
                vm.bills = store.get('bills');
                billsLocal = vm.bills.receipts;                
                vm.loading = false;
            } else {
                getBillsService(1);
            }        
        } else {
            vm.loading = false;
            popUpopen = true;
            myPopup = $ionicPopup.show({
                title: '<i class="icon ion-alert-circled openy fsBig"></i>',
                template: 'Debes tener una cuenta validada para ver tus recibos',
                scope: $scope,
                buttons: [
                    {
                        text: 'registrarme',
                        type: 'positive',
                        onTap: function(e){
                            $ionicHistory.nextViewOptions({
                                disableAnimate: false,
                                disableBack: true,
                                historyRoot: true
                            });
                            myPopup.close();
                            $state.go('app.login');                            
                        }
                    }
                ]
            });
        }

    });
};


angular.module('starter.bills', [])
    .controller('billsController', ['$scope', '$rootScope', '$state', '$ionicSlideBoxDelegate', '$ionicPopup', 'store', 'billsService', '$ionicHistory', '$timeout', billsCtrl])