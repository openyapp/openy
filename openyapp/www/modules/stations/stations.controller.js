var stationsCtrl = function stationsCtrl($scope, stationsService, store, $ionicScrollDelegate, $ionicListDelegate, $timeout, $ionicLoading, $rootScope, $ionicNavBarDelegate, MapService) {
    'use estrict';

    var moveCamera = true,
        data = {},
        position = store.get('position') || {
            lat: 0,
            lng: 0,
            zoom: 8,
        },
        initMap,
        getStations,
        callBackStations,
        timer = false,
        i = 0;
    $scope.openy = false;
    $scope.favorites = false;
    $scope.position = {};
    $scope.position.lat = false;
    $scope.position.lng = false;
    $scope.position.zoom = 14;
    $scope.selector = "#map_canvas";
    $scope.submenuMap = false;
    $scope.stationInfo = false;
    $rootScope.myPosition = position;
    $scope.stations = [];
    $scope.init = 0;
    if (store.get('updateStations')) {
        $scope.init = 1;
    }
    console.log('settings',store.get('settings'));

    MapService.removeMarkers();

    if ($rootScope.myPosition) {
        if ($rootScope.myPosition.lat) {
            $scope.lat = $rootScope.myPosition.lat;
        }
        if ($rootScope.myPosition.lng) {
            $scope.lng = $rootScope.myPosition.lng;
        }
    }

    $scope.goStationGps = function (stations) {
        myLatLng = new plugin.google.maps.LatLng(position.lat, position.lng);
        latLng = new plugin.google.maps.LatLng(stations.lat, stations.lng);
        console.log(myLatLng + ' y la station ' + position);
        plugin.google.maps.external.launchNavigation({
            "from": myLatLng,
            "to": latLng,
            "travelMode": "driving"
        });
    }



    //When click on mark
    $rootScope.$on('mapClickStation', function (event, station) {
        console.log('click en ' + station);

        stationsService.getStationFuel(station.idstation).then(function (data) {

            station.fueltypes = data;

        });

        $scope.submenuMap = false;
        $scope.$apply(function () {
            station.distance = station.distance.toFixed(0) / 1000;

            if (station.distance < 1) {
                station.distance = station.distance * 1000;
                station.metric = "m";
            } else {
                station.metric = "km";
            }
            $scope.stationInfo = station;
            console.log(station);
        });
    });



    $scope.showSubmenuMap = function () {
        console.log('menu');
        if ($scope.submenuMap) $scope.submenuMap = false;
        else $scope.submenuMap = true;
    }

    //Close div map station selected
    $scope.hideStationboxMap = function () {
        $scope.stationInfo = null;
        MapService.setClickable(true);
    }

    $scope.setFavorites = function (obj) {
        console.log(obj);
                                if(obj.favorite){
                                    console.log('existe');
                                    stationsService.removeFavorite(obj.idstation);
                                    $scope.stationInfo.favorite = false;
                                    if($scope.favorites){
                                     var index = $scope.stations.indexOf(obj);
                                        console.log('index',index);
                                        if (index > -1) {
                                            $scope.stations.splice(index, 1);
                                            MapService.removeMarker($scope.stationInfo.marker);
                                        }
                                    }
                                }else{
                                    stationsService.addFavorite(obj.idstation);
                                    $scope.stationInfo.favorite = true;
                                    if($scope.favorites)$scope.stations.push(obj);
                                    MapService.addMarker($scope.stationInfo);
                                }




    }


    //Load Map
    initMap = function initMap() {
    	console.log('init map');
        MapService.createMap($scope.selector, $scope.position.lat, $scope.position.lng, function () {
        	console.log('create map');
            MapService.setClickable(true);
            MapService.myLocation(moveCamera).then(function (location) {
                moveCamera = false;
                $scope.position.lat = location.lat;
                $scope.position.lng = location.lng;
            });
            console.log('map -- 1 --');
            if (!moveCamera) {
                callBackStations($scope.stations, $scope.stations.length);
            }
            console.log('map -- 2 --');
        }, function (positionChanged) {
        	console.log('map -- 3 --');
            var changed = true;
            if (positionChanged.zoom > $scope.position.zoom && $scope.position.lat == positionChanged.target.lat && $scope.position.lng == positionChanged.target.lng) {
                changed = false;
            }
            $scope.zoom = positionChanged.zoom;
            $scope.position.lat = positionChanged.target.lat;
            $scope.position.lng = positionChanged.target.lng;
            MapService.getVisibleRegion().then(function (region) {
                console.log('get region', region);
                $scope.position.northeast = region.northeast;
                $scope.position.southwest = region.southwest;
                if (changed) {
                    if ($scope.zoom > 8) {
                        console.log('cargo nuevas estations');

                            getStations();



                    } else {
                        window.plugins.toast.showShortBottom('Necesitas hacer zoom para ver mas empresas');
                    }
                }
            });

        });
        console.log('map -- 4 --');
        
    };


    //Get Stations
    getStations = function () {
    	console.log('map -- 5 --');

        if (i == 0) {
                var timeDelay = 1;
            } else { // Sino esperamos un segundo para cargarlo
                var timeDelay = 1000;
            }
            $scope.cargando = true;
            if (timer) {
                $timeout.cancel(timer);
            }

            timer = $timeout(function () {


                if ($scope.stations.length === 0) {
                    $scope.loading = true;
                }
                data.position = {};
                data.position.lat = $scope.position.lat;
                data.position.lng = $scope.position.lng
                data.position.northeast = $scope.position.northeast;
                data.position.southwest = $scope.position.southwest;
                data.fuelcode = "GOA";
                data.openy = $scope.openy;
                data.favorites = $scope.favorites;
                console.log(data);
                if ($scope.init === 0) {
                    $rootScope.$on('stationDownloaded', function () {
                        stationsService.loadStations(data).then(function (data) {
                            callBackStations(data, data.length);
                        console.log('fin carga estation');
                        });
                    });
                } else {


                    stationsService.loadStations(data).then(function (data) {
                        callBackStations(data, data.length);
                        console.log('fin carga estation');
                    });
                }

                 i++;
            }, timeDelay);





    };


    //Show new stations into map
    callBackStations = function (stations, total) {
        console.log('estaciones',stations);
        console.log('total estaciones '+stations.length);
        $scope.init = 1;
        if ($scope.page === 0) {
            MapService.removeMarkers();
            $scope.page = 1;
        }
        if (total === 0) {
            $scope.stations = [];
            $scope.noResults = true;
            // window.plugins.toast.showShortCenter('No se han encontrado Gasolineras');
        } else {
            MapService.removeMarkers();
            $scope.stations = [];
            $scope.noResults = false;
            MapService.addStations(stations);
            $scope.mapStationInfo = MapService.mapStationInfo;
            $scope.stations = stations;
        }
        $scope.loading = false;
        $ionicLoading.hide();
    };

    $scope.filterChange = function (id) {

        console.log('entro en filterchange');
        MapService.resetCollectionMarkers();
        $scope.page = 0;
        if (id == 1) {
          //  $scope.favorites = false;
            if ($scope.openy) $scope.openy = false;
            else $scope.openy = id;
        } else {
            //$scope.openy = false;
            if ($scope.favorites) $scope.favorites = false;
            else $scope.favorites = 1;

        }
        getStations();
        $scope.submenuMap = false;
    };

    $scope.$on('$ionicView.enter', function () {
        MapService.resetCollectionMarkers();
        $scope.mapHeight = document.getElementById('mapContent').offsetHeight;
        initMap();
    });

};


/*function dynamicSort(property) {
    var sortOrder = 1;
    if(property[0] === "-") {
        sortOrder = -1;
        property = property.substr(1);
    }
    return function (a,b) {
        var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
        return result * sortOrder;
    }
}*/

var stationsListCtrl = function stationsListCtrl($scope, stationsService, store, $ionicScrollDelegate, $ionicListDelegate, $timeout, $ionicLoading, $rootScope, $ionicNavBarDelegate, MapService) {
    'use estrict';
    var position = store.get('position'),
        data = {};
    $scope.submenuMap = false;
    $scope.loadMore = false;
    $scope.page = 0;
    $scope.stations = [];
    $scope.filter = false;
    $scope.field = "distance";
    $scope.directionorder = "ASC";
    $scope.fuelcode = "GOA";
    $scope.radioAction = store.get('defaultDistance') || 50;
    /* var position = {};
     position.lat = 43.3571871;
     position.lng = -8.407468;*/
    $scope.position = position;
        console.log('settings',store.get('settings'));

    stationsService.getFuelType().then(function(obj){

        console.log('getFuelType',obj);

        $scope.fuelListTypes = obj;

    },function(error){console.log('error getFuelType',error)});






    $rootScope.myPosition = position;


    MapService.setClickable(false);

    $scope.showSubmenuMap = function () {
        if ($scope.submenuMap) $scope.submenuMap = false;
        else $scope.submenuMap = true;
    }

    $scope.setOrder = function (type,code) {
        console.log(code);
        $scope.page = 0;
        $scope.loadMore = false;
        $scope.stations = [];
        $scope.submenuMap = false;
        if(type == "fuelcode"){
            $scope.field = "distance";
            $scope.fuelcode = code;
            $scope.fuelTypesActive=true;

        }else{
            $scope.field = type;
        }

        getStations();

    }

    $scope.setFilter = function (type) {
        $scope.stations = [];
        if($scope.filter == type)$scope.filter = false;
        else $scope.filter = type;

        $scope.page = 0;
        $scope.loadMore = false;
       // $scope.fuelTypesActive=false;

        getStations();

    }

    $scope.getMoreStations = function () {

        if($scope.loadMore){
            console.log('cargo mas' + $scope.page);
            $scope.page = (parseInt($scope.page) + parseInt(10));
            console.log($scope.page);
            getStations();
        }
    }



    var getStations = function getStations() {
        console.log('cargo stations');
        data.page = $scope.page;
        data.direction = $scope.directionorder;
        data.field = $scope.field;
        data.fuelcode = $scope.fuelcode;
        data.position = $scope.position;
        data.radioAction = $scope.radioAction;
        data.filter = $scope.filter;
        console.log(data);
        var response = stationsService.getStations(data);

        response.then(function (obj) {
            console.log(obj);
            for (var i = 0; i < obj.length; i++) {
                obj[i].distance = MapService.distanceToMe(obj[i]) / 1000;
                obj[i].distance = obj[i].distance.toFixed(2);

                if (obj[i].distance < 1) {
                    obj[i].distance = obj[i].distance * 1000;
                    obj[i].metric = "m";
                } else {
                    obj[i].metric = "km";
                }
            }
            //$scope.items = obj;
            if (obj.length > 0){
                console.log('cargo ladMore');
                $scope.loadMore = true;
            } else {
                console.log('no cargo ladMore');
                $scope.loadMore = false;
            }

            Array.prototype.push.apply($scope.stations, obj);
            $scope.$broadcast('scroll.infiniteScrollComplete');

        }, function (e) {
            console.log(e)
        });

    }

    $scope.goStationGps = function (stations) {
        myLatLng = new plugin.google.maps.LatLng(position.lat, position.lng);
        latLng = new plugin.google.maps.LatLng(stations.lat, stations.lng);
        console.log(myLatLng + ' y la station ' + position);
        plugin.google.maps.external.launchNavigation({
            "from": myLatLng,
            "to": latLng,
            "travelMode": "driving"
        });
    }

    $scope.$on('$ionicView.enter', function () {
        getStations();
    });

}

var stationCtrl = function stationCtrl($scope, stationsService, store, $ionicScrollDelegate, $ionicListDelegate, $timeout, $ionicLoading, $rootScope, $ionicNavBarDelegate, $stateParams, MapService) {
    'use estrict';
    var position = store.get('position'),
        data = {};

    $scope.stationInfo = false;
    $scope.height = 200;
    var moveCamera = false;
    
    var station = angular.fromJson($stateParams.station);
    $scope.stationInfo = station;
        console.log(station);
    
    stationsService.getStationFuel(station.idstation).then(
        function(data){
            console.log(data);
            $scope.stationInfo.fueltypes = data;
            console.log($scope.stationInfo);
        },
        function(error){
        });
    
    var getStation = function(){
    
    MapService.createMap('#mapStation', station.lat, station.lng, function () {
                             MapService.addMarker(station);
                             MapService.setClickable(false);
                         }, null, 16);
    }

    //angular.fromJson($stateParams.refueling);
    $scope.goStationGps = function (stations) {
        myLatLng = new plugin.google.maps.LatLng(position.lat, position.lng);
        latLng = new plugin.google.maps.LatLng(stations.lat, stations.lng);
        console.log(myLatLng + ' y la station ' + position);
        plugin.google.maps.external.launchNavigation({
            "from": myLatLng,
            "to": latLng,
            "travelMode": "driving"
        });
    }
    
    $scope.setFavorites = function (obj) {
        console.log(obj);
            if(station.favorite){
                console.log('existe');
                stationsService.removeFavorite(obj.idstation);
                $scope.stationInfo.favorite = false;
                if($scope.favorites){
                 var index = $scope.stations.indexOf(obj);
                    console.log('index',index);
                    if (index > -1) {
                        $scope.stations.splice(index, 1);
                        MapService.removeMarker($scope.stationInfo.marker);
                    }
                }
            }else{
                stationsService.addFavorite(obj.idstation);
                $scope.stationInfo.favorite = true;
                if($scope.favorites)$scope.stations.push(obj);
                MapService.addMarker($scope.stationInfo);
            }


    }

    $scope.$on('$ionicView.enter', function () {
        getStation();
    });

}

angular.module('starter.stations', [])
    .controller('stationsCtrl', ['$scope', 'stationsService', 'store', '$ionicScrollDelegate', '$ionicListDelegate', '$timeout', '$ionicLoading', '$rootScope', '$ionicNavBarDelegate', 'MapService', stationsCtrl])
    .controller('stationsListCtrl', ['$scope', 'stationsService', 'store', '$ionicScrollDelegate', '$ionicListDelegate', '$timeout', '$ionicLoading', '$rootScope', '$ionicNavBarDelegate', 'MapService', stationsListCtrl])
    .controller('stationCtrl', ['$scope', 'stationsService', 'store', '$ionicScrollDelegate', '$ionicListDelegate', '$timeout', '$ionicLoading', '$rootScope', '$ionicNavBarDelegate', '$stateParams', 'MapService', stationCtrl])