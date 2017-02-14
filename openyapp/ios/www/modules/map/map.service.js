(function () {
    'use strict';
    angular.module('starter')
        .factory("MapService", ["$http", "$q", "store", "$rootScope", "$ionicPlatform", "stationsService", 'authService', function ($http, $q, store, $rootScope, $ionicPlatform, stationsService, authService) {
            var rad = Math.PI / 180,
                map = false,
                collectionMarkers = [],
                loadMap = function loadMap(selector, lat, lng, mapReady, mapCameraChanged, zoom) {
                    var zoom = zoom || 14;
                    var div = document.querySelector(selector),
                        params = {
                            'controls': {
                                'compass': true,
                                'myLocationButton': true,
                                'indoorPicker': true,
                                // 'zoom': true // Only for Android
                            }
                        },
                        currentView = document.querySelector('ion-view[nav-view="active"]');

                    if (map) {
                        map.setMyLocationEnabled(true);
                        map.setDiv(div);
                        if (lat && lng) {
                            moveCamera(lat, lng, zoom);
                        }
                        if (mapReady) {
                            mapReady();
                        }
                    } else {
                        if (lat && lng) {
                            var latLng = new plugin.google.maps.LatLng(lat, lng);
                            params.camera = {
                                'latLng': latLng,
                                'tilt': 0,
                                'zoom': zoom //position.zoom
                            };
                        }
                        map = plugin.google.maps.Map.getMap(div, params);
                        // Wait until the map is ready status.
                        map.addEventListener(plugin.google.maps.event.MAP_READY, function () {
                            if (mapReady) {
                                mapReady();
                            }
                        });
                    }
                    if (mapCameraChanged) {
                        map.on(plugin.google.maps.event.CAMERA_CHANGE, mapCameraChanged);
                    }
                },
                moveCamera= function moveCamera(lat, lng, zoom) {
                    var latLng = lat;
                    zoom = zoom || 14;
                    if (typeof (lng) !== "undefined") {
                        latLng = new plugin.google.maps.LatLng(lat, lng);
                    }
                    map.moveCamera({
                        target: latLng,
                        tilt: 0,
                        zoom: zoom
                    });
                },
                addMarker= function addMarker(stations) {
                    var latLng = new plugin.google.maps.LatLng(stations.lat, stations.lng);
                    // if(station.name != "meroil" && station.name != "petronieves") var icon = "repsol_icon";
                    var icon = stations.logo + '_icon.png';
                    if (stations.openy > 0) icon = stations.logo + '_openy_icon.png';

                    if (window.devicePixelRatio > 2) {
                        icon = stations.logo + '_icon@3.png';
                        if (stations.openy > 0) icon = stations.logo + '_openy_icon@3.png';
                    } else if (window.devicePixelRatio > 1) {
                        icon = stations.logo + '_icon@2.png';
                        if (stations.openy > 0) icon = stations.logo + '_openy_icon@2.png';
                    }

                    var size = {
                        width: 43,
                        height: 66
                    };

                    if (stations.openy > 0) {
                        size = {
                            width: 69,
                            /*72*/
                            height: 100
                        };
                    }

                    map.addMarker({
                        'position': latLng,
                        //'title': station.name + "\n" + station.address,
                        'icon': {
                            url: 'www/img/icons_stations/' + icon,
                            size: size
                        },
                        'dataStations': stations
                    }, function (marker) {
                        marker.addEventListener(plugin.google.maps.event.MARKER_CLICK, function (event) {
                            var station = marker.get('dataStations');
                            station.marker = marker;
                            station.distance = distanceToMe({
                                lat: station.lat,
                                lng: station.lng
                            });
                            $rootScope.$broadcast('mapClickStation', station);
                        });
                    });
                },
                distanceToMe= function distanceToMe(position) {
                    var lat1 = $rootScope.myPosition.lat * rad,
                        lat2 = position.lat * rad,
                        a = Math.sin(lat1) * Math.sin(lat2) + Math.cos(lat1) * Math.cos(lat2) * Math.cos((position.lng - $rootScope.myPosition.lng) * rad);
                    return 6378137 * Math.acos(Math.min(a, 1));
                };
            return {
                resetCollectionMarkers: function resetCollectionMarkers() {

                    collectionMarkers = [];
                },
                distanceToMe: distanceToMe,
                moveCamera: moveCamera,
                loadMap: loadMap,
                createMap: function createMap(selector, lat, lng, mapReady, mapCameraChanged, zoom) {
                    if (map) {
                        map.clear();
                        map.off();
                        map.setClickable(true);
                        loadMap(selector, lat, lng, mapReady, mapCameraChanged, zoom);
                    } else {
                        if (typeof (plugin) !== "undefined") {
                            loadMap(selector, lat, lng, mapReady, mapCameraChanged, zoom);
                        } else {
                            $ionicPlatform.ready(function () {
                                if (typeof (plugin) !== "undefined") {
                                    loadMap(selector, lat, lng, mapReady, mapCameraChanged, zoom);
                                }
                            });
                        }
                    }
                },
                myLocation: function myLocation(move, refuel) {
                    var deferrer = $q.defer(),
                        getMyLocation;
                    var highAccuracy = {
                        enableHighAccuracy: false
                    };
                    if (refuel) {
                        highAccuracy = {
                            enableHighAccuracy: true
                        };
                    }
                    getMyLocation = function () {
                        map.getMyLocation(highAccuracy, function (location) {
                            $rootScope.myPosition = $rootScope.myPosition || {};
                            $rootScope.myPosition.lat = location.latLng.lat;
                            $rootScope.myPosition.lng = location.latLng.lng;
                            store.set('position', $rootScope.myPosition);
                            authService.clientRegisterLocation(location.latLng.lat, location.latLng.lng);
                            if (move) {
                                moveCamera(location.latLng.lat, location.latLng.lng);
                            }
                            deferrer.resolve($rootScope.myPosition);
                        }, function (error) {
                            var position = store.get('position');
                            if (position && !refuel) {
                                $rootScope.myPosition = $rootScope.myPosition || {};
                                $rootScope.myPosition.lat = position.lat;
                                $rootScope.myPosition.lng = position.lng;
                                authService.clientRegisterLocation(position.lat, position.lng);
                                deferrer.resolve($rootScope.myPosition);
                            } else {
                                authService.clientRegisterLocation(0, 0);
                                deferrer.reject(error);
                            }
                        });
                    };
                    if (map) {
                        getMyLocation();
                    } else {

                        if (typeof (plugin) !== "undefined") {
                            plugin.google.maps.Map.isAvailable(function(isAvailable, message) {
                                if (isAvailable) {
                                    if(!map) {
                                        map = plugin.google.maps.Map.getMap();
                                        map.addEventListener(plugin.google.maps.event.MAP_READY, function (mapB) {
                                            getMyLocation();
                                        });
                                    } else {
                                        getMyLocation();
                                    }

                                } else {
                                    console.log('error', message);
                                }
                            });


                        }
                    }
                    return deferrer.promise;
                },
                getVisibleRegion: function getVisibleRegion() {
                    var deferrer = $q.defer();
                    map.getVisibleRegion(function (latLngBounds) {
                        var region = {};
                        region.northeast = latLngBounds.northeast.toUrlValue();
                        region.southwest = latLngBounds.southwest.toUrlValue();
                        deferrer.resolve(region);

                    }, function (error) {
                        deferrer.reject(error);
                    });
                    return deferrer.promise;
                },
                addStations: function addStations(stations) {
                    var i = 0,
                        latLng;

                    //console.log('collectionMarkers',collectionMarkers);

                    for (i; i < stations.length; i = i + 1) {
                        addMarker(stations[i]);
                    }
                },
                addMarker: addMarker,
                removeMarker: function removeMarker(station) {
                    station.remove();
                },
                removeMarkers: function removeMarkers() {
                    var collectionMarkers = [];
                    if (map) {

                        map.clear();
                    }
                },
                setClickable: function setClickable(check) {
                    if (map) {
                        map.setClickable(check);
                    }
                },
                refreshLayout: function refreshLayout() {
                    if (map) {
                        map.refreshLayout();
                    }
                }
            };
        }]);
}());
