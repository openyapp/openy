angular.module('starter.services', [])

.factory('General', function ($http, store, $ionicLoading, $rootScope) {
    var d = new Date();
    //d.setDate(d.getDate() + 2);
    var n = new Date(d.getFullYear(), d.getMonth(), d.getDate());
    var dateToday = n.valueOf() / 1000;
    var dateUpdate = false;

    var dateTodayCons = d.toISOString().slice(0, 10).replace(/-/g, "-");
    
    if(store.get('updateStations') && store.get('updateStations') > 0)
    {
        var dateU = new Date(store.get('updateStations')  * 1000);
        var dateUpdate = dateU. toISOString().slice(0, 10);
    }


    var existBbdd = function () {
        if (store.get('initBddd')) {
            return true;
        } else {
            console.log('instalacion inicial');
            //inicializeTables();
            return false;
        }
    }

    var errorBbdd = function (err) {
        console.log(err);
    }

    var inicializeTables = function () {

        db.transaction(function (tx) {
            tx.executeSql('DROP TABLE IF EXISTS stations');
            tx.executeSql('DROP TABLE IF EXISTS prices');
            tx.executeSql('DROP TABLE IF EXISTS fueltype');
            tx.executeSql('CREATE TABLE IF NOT EXISTS stations(idoffstation integer primary key, name text, address text ,openy,recommended, logo,date, lat, lng, cos_lat, sin_lat, cos_lng, sin_lng)');
            tx.executeSql('CREATE TABLE IF NOT EXISTS prices(idoffstation integer, idfueltype integer, price,date)');
            tx.executeSql('CREATE TABLE IF NOT EXISTS fueltype(idfueltype integer primary key, fueltype, fuelcode,date)');

        });
    }


    var updateStations = function (station) {
        var param = "psize=all";


        if (dateUpdate) {
            var param = "psize=all&action=update&date=" + dateUpdate;
        } else {
            inicializeTables();
            var param = "psize=all";
        }

        //$http.get('http://:80/station?' + param)
        $http.get('stations.json')

        .then(function (res) {

            // Converts from degrees to radians.
            Math.radians = function (degrees) {
                return degrees * Math.PI / 180;
            };

            // Converts from radians to degrees.
            Math.degrees = function (radians) {
                return radians * 180 / Math.PI;
            };

            var d = new Date();
            var n = d.getTime();

            var datos = res.data;


            db.transaction(function (tx) {
                tx.executeSql('CREATE TABLE IF NOT EXISTS stations(idoffstation integer primary key, name text, address text, openy, recommended, logo,date, lat, lng, cos_lat, sin_lat, cos_lng, sin_lng)', [], function (tx, res) {

                    for (var i in datos) {

                        // console.log(datos[i]);
                        var data = datos[i];
                        data.lat = data.ilat;
                        //var lat = lat.replace(",", "."); 
                        data.lng = data.ilng;
                        //var lng = lng.replace(",", "."); 

                        data.cos_lat = Math.cos(Math.radians(data.lat));
                        data.cos_lng = Math.cos(Math.radians(data.lng));
                        data.sin_lat = Math.sin(Math.radians(data.lat));
                        data.sin_lng = Math.sin(Math.radians(data.lng));
                        //console.log("select idoffstation from stations where idoffstation='"+data.idoffstation+"'");
                        tx.executeSql("select idoffstation from stations where idoffstation='" + data.idoffstation + "'", [], (function (data) {
                                return function (tx, res) //success function
                                    {
                                        if (res.rows.length > 1) {
                                            //  console.log('UPDATE  stations SET  name="'+data.name+'",address="'+data.name+'",openy = "openy",date='+n+',lat ='+data.lat+',lng = '+data.lng+',cos_lat='+data.cos_lat+',sin_lat='+data.sin_lat+',cos_lng='+data.cos_lng+',sin_lng='+data.sin_lng+' where idoffstation='+data.idoffstation);
                                            tx.executeSql('UPDATE  stations SET  name="' + data.name + '",address="' + data.address + '",openy = "' + data.idopeny + '",recommended = "' + data.recommended + '",logo = "' + data.logoname + '",date=' + n + ',lat =' + data.lat + ',lng = ' + data.lng + ',cos_lat=' + data.cos_lat + ',sin_lat=' + data.sin_lat + ',cos_lng=' + data.cos_lng + ',sin_lng=' + data.sin_lng + ' where idoffstation=' + data.idoffstation);

                                        } else {
                                            tx.executeSql('INSERT INTO stations (idoffstation,name,address,openy,recommended,logo,date,lat,lng,cos_lat,sin_lat,cos_lng,sin_lng) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?) ', [data.idoffstation, data.name, data.address, data.idopeny, data.recommended, data.logoname, n, data.lat, data.lng, data.cos_lat, data.sin_lat, data.cos_lng, data.sin_lng]);

                                        }

                                    };
                            })(data),
                            errorBbdd);



                    }
                    store.set('updateStations', dateToday);
                    $rootScope.$broadcast('stationDownloaded');
                    $ionicLoading.hide();
                });

            }, function (err) {
                console.log(err)
            });

        }, function (err) {
            console.log(err)
        });

    }

    var updateFueldType = function () {

        //$http.get('http://:80/fueltype')
        $http.get('fuel_types.json')
            .then(function (res) {


                var d = new Date();
                var n = d.getTime();

                var datos = res.data;


                db.transaction(function (tx) {

                    tx.executeSql('CREATE TABLE IF NOT EXISTS fueltype(idfueltype integer primary key, fueltype, fuelcode,date)', [], function (tx, res) {

                        for (var i in datos) {

                            //  console.log(datos[i]);
                            var data = datos[i];

                            tx.executeSql("select idfueltype from fueltype where idfueltype=" + data.idfueltype, [], (function (data) {
                                    return function (tx, res) //success function
                                        {

                                            if (res.rows.length > 1) {
                                                console.log(res);
                                                console.log('existe tipo  gasolina ' + data.fueltype);
                                                console.log('UPDATE fueltype set fuelcode="' + data.fueltype + '",date=' + n + ' where fueltype="' + data.fueltype + '"');
                                                tx.executeSql('UPDATE fueltype set fuelcode="' + data.fuelcode + '",date=' + n + ',fueltype="' + data.fueltype + '" where fueltype="' + data.fueltype + '"');

                                            } else {

                                                tx.executeSql('INSERT INTO fueltype (idfueltype,fueltype,fuelcode,date) VALUES (?,?,?,?) ', [data.idfueltype, data.fueltype, data.fuelcode, n]);
                                            }


                                        };
                                })(data),
                                errorBbdd);


                        }
                        store.set('updateFueldType', dateToday);

                    });

                }, function (err) {
                    console.log(err)
                });

            }, function (err) {
                console.log(err)
            });

    }

    var updatePrices = function () {

        if ( dateUpdate ) {
            var param = "psize=all&action=update&date=" + dateUpdate;
        } else {
            var param = "psize=all";
        }

        //$http.get('http://:80/price?' + param)
        $http.get('stations_price.json')

        .then(function (res) {


            var d = new Date();
            var n = d.getTime();

            var datos = res.data;


            db.transaction(function (tx) {
                tx.executeSql('CREATE TABLE IF NOT EXISTS prices(idoffstation integer, idfueltype integer, price,date)', [], function (tx, res) {

                    for (var i in datos) {

                        // console.log(datos[i]);
                        var data = datos[i];

                        types = data.fueltypes.split(",");
                        prices = data.prices.split(",");

                        for (var i = 0; i < types.length; i++) {

                            var pricesC = {};
                            pricesC.idoffstation = data.idoffstation;
                            pricesC.type = types[i];
                            pricesC.price = prices[i];

                            tx.executeSql("select idoffstation from prices where idoffstation=" + data.idoffstation + " and idfueltype=" + types[i], [], (function (pricesC) {
                                    return function (tx, res) //success function
                                        {

                                            if (res.rows.length > 1) {
                                                console.log('UPDATE prices set price=' + pricesC.price + ',date=' + n + ' where idoffstation=' + pricesC.idoffstation + ' and idfueltype=' + pricesC.type);
                                                tx.executeSql('UPDATE prices set price=' + pricesC.price + ',date=' + n + ' where idoffstation=' + pricesC.idoffstation + ' and idfueltype=' + pricesC.type);

                                            } else {

                                                tx.executeSql('INSERT INTO prices (idoffstation,idfueltype,price,date) VALUES (?,?,?,?) ', [pricesC.idoffstation, pricesC.type, pricesC.price, n]);
                                            }

                                            //console.log('en la gasolinera '+data.idoffstation+'type '+types[i]+' vale '+prices[i]);


                                        };
                                })(pricesC),
                                errorBbdd);


                            // tx.executeSql('INSERT INTO prices (idoffstation,fueltype,price,date) VALUES (?,?,?,?) ',[data.idfueltype,data.fueltype,data.fuelcode, n]);


                        }
                    }
                    store.set('updatePrices', dateToday);

                    console.log('acabamos empresas');
                });

            }, function (err) {
                console.log(err)
            });

        }, function (err) {
            console.log(err)
        });

    }


    var updateStations_old = function () {

        $http.get('gasolineras_es_json.json')
            .then(function (res) {

                // Converts from degrees to radians.
                Math.radians = function (degrees) {
                    return degrees * Math.PI / 180;
                };

                // Converts from radians to degrees.
                Math.degrees = function (radians) {
                    return radians * 180 / Math.PI;
                };

                var d = new Date();
                var n = d.getTime();

                var datos = res.data;

                db.transaction(function (tx) {
                    tx.executeSql('CREATE TABLE IF NOT EXISTS stations(idoffstation integer primary key, name text, address text, openy, date, lat, lng, cos_lat, sin_lat, cos_lng, sin_lng)', [], function (tx, res) {

                        for (var i = 0; i < datos.length; i++) {
                            var data = datos[i];
                            var lat = data.lat;
                            var lat = lat.replace(",", ".");
                            var lng = data.lng;
                            var lng = lng.replace(",", ".");

                            var cos_lat = Math.cos(Math.radians(lat));
                            var cos_lng = Math.cos(Math.radians(lng));
                            var sin_lat = Math.sin(Math.radians(lat));
                            var sin_lng = Math.sin(Math.radians(lng));
                            // tx.executeSql('INSERT INTO gasolineras (id,idstation,name,logo,openy,photo,date,lat,lng,cos_lat,sin_lat,cos_lng,sin_lng) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?) ',[i,data.idstation, data.name, data.log,data.openy, data.photo,n,data.lat, data.lng, cos_lat, sin_lat, cos_lng, sin_lng]);
                            tx.executeSql('INSERT INTO stations (idoffstation,name,address,openy,date,lat,lng,cos_lat,sin_lat,cos_lng,sin_lng) VALUES (?,?,?,?,?,?,?,?,?,?,?,?) ', [i, data.tipo, "", "openy", "", n, lat, lng, cos_lat, sin_lat, cos_lng, sin_lng]);
                            tx.executeSql('INSERT INTO prices (idstation,idtype,price,date) VALUES (?,?,?,?) ', [i, 1, data.gasoleoA, n]);
                            tx.executeSql('INSERT INTO prices (idstation,idtype,price,date) VALUES (?,?,?,?) ', [i, 2, data.gasolina, n]);

                        }
                        tx.executeSql('INSERT INTO fueltype (idtype,type,date) VALUES (?,?,?) ', [1, "GasoleoA", n]);
                        tx.executeSql('INSERT INTO fueltype (idtype,type,date) VALUES (?,?,?) ', [2, "Gasolina95", n]);

                        console.log('acabamos empresas');
                    });

                }, function (err) {
                    console.log(err)
                });

            }, function (err) {
                console.log(err)
            });

    }
    var updateCompanies = function () {
        //window.plugins.toast.showShortBottom('Estamos cargando datos del mapa');
        $http.get('gasolineras_es_json.json?dfgs')
            .then(function (res) {

                // Converts from degrees to radians.
                Math.radians = function (degrees) {
                    return degrees * Math.PI / 180;
                };

                // Converts from radians to degrees.
                Math.degrees = function (radians) {
                    return radians * 180 / Math.PI;
                };

                var datos = res.data;

                db.transaction(function (tx) {
                    tx.executeSql('DROP TABLE IF EXISTS gasolineras');
                    tx.executeSql('CREATE TABLE IF NOT EXISTS gasolineras(id integer primary key, Provincia text, Municipio text, Localidad text, Lat, Lng, Tipo, cos_lat, sin_lat, cos_lng, sin_lng, GasoleoA)', [], function (tx, res) {

                        for (var i = 0; i < datos.length; i++) {

                            var lat = datos[i].lat;
                            var lat = lat.replace(",", ".");
                            var lng = datos[i].lng;
                            var lng = lng.replace(",", ".");

                            var cos_lat = Math.cos(Math.radians(lat));
                            var cos_lng = Math.cos(Math.radians(lng));
                            var sin_lat = Math.sin(Math.radians(lat));
                            var sin_lng = Math.sin(Math.radians(lng));
                            tx.executeSql('INSERT INTO gasolineras (id,Provincia,Municipio,Localidad,Lat,Lng,Tipo,cos_lat,sin_lat,cos_lng,sin_lng,GasoleoA) VALUES (?,?,?,?,?,?,?,?,?,?,?,?) ', [i, datos[i].Provincia, datos[i].Municipio, datos[i].Localidad, lat, lng, datos[i].tipo, cos_lat, sin_lat, cos_lng, sin_lng, datos[i].gasoleoA]);

                        }

                        console.log('acabamos empresas');
                    });

                }, function (err) {
                    console.log(err)
                });

            }, function (err) {
                console.log(err)
            });

    }

    var getFavorites = function () {
        var result = [];
        db.transaction(function (tx) {
            tx.executeSql('CREATE TABLE IF NOT EXISTS favorites(idoffstation integer primary key,date)');
            tx.executeSql("select *  from  favorites", [], function (tx, res) {
                if (res.rows.length > 0) {

                    for (var i = 0; i < res.rows.length; i++) {
                        console.log(res.rows.item(i));
                        result.push(res.rows.item(i).idoffstation)
                    }

                    store.set('favorites', result);

                };

            }, function (e) {

                console.log(e);
            });
        }, function (err) {
            console.log(err)
        });


    }
    return {
        updateCompanies: updateCompanies,
        inicializeTables: inicializeTables,
        updateStations: updateStations,
        updatePrices: updatePrices,
        updateFueldType: updateFueldType,
        getFavorites: getFavorites
    }
})


;