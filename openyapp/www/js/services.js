angular.module('starter.services', [])

.factory('General', function ($http, store, $ionicLoading, $rootScope, CONFIG) {
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
            tx.executeSql('CREATE TABLE IF NOT EXISTS stations(idoffstation integer primary key, name text, address text ,openy, recommended, favorite, logo , date, lat, lng, cos_lat, sin_lat, cos_lng, sin_lng)');
            tx.executeSql('CREATE TABLE IF NOT EXISTS prices(idoffstation integer, idfueltype integer, price,date)');
            tx.executeSql('CREATE TABLE IF NOT EXISTS fueltype(idfueltype integer primary key, fueltype, fuelcode,date)');

        });
        //alert('inicializamos');
    }


    var updateStations = function (station) {
        var param = "psize=all";


        if (dateUpdate) {
            var param = "psize=all&action=update&date=" + dateUpdate;
        } else {
            inicializeTables();
            var param = "psize=all";
        }
        var skipAuthorization= true;
        if (store.get('token')) {
            skipAuthorization= false;
        }

        $http.get(CONFIG.APIURL + 'station?' + param, {skipAuthorization: skipAuthorization})
            //$http.get('stations.json')

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
                tx.executeSql('CREATE TABLE IF NOT EXISTS stations(idoffstation integer primary key, name text, address text, openy, recommended, favorite, logo,date, lat, lng, cos_lat, sin_lat, cos_lng, sin_lng)', [], function (tx, res) {

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
                                            alert('update');
                                            tx.executeSql('UPDATE  stations SET  name="' + data.name + '",address="' + data.address + '",openy = "' + data.idopeny + '", recommended = "' + data.recommended + '", favorite ="'+data.favorite+'",logo = "' + data.logoname + '",date=' + n + ',lat =' + data.lat + ',lng = ' + data.lng + ',cos_lat=' + data.cos_lat + ',sin_lat=' + data.sin_lat + ',cos_lng=' + data.cos_lng + ',sin_lng=' + data.sin_lng + ' where idoffstation=' + data.idoffstation);

                                        } else {
                                            tx.executeSql('INSERT INTO stations (idoffstation,name,address,openy,recommended,favorite,logo,date,lat,lng,cos_lat,sin_lat,cos_lng,sin_lng) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?) ', [data.idoffstation, data.name, data.address, data.idopeny, data.recommended,parseInt(data.favorite), data.logoname, n, data.lat, data.lng, data.cos_lat, data.sin_lat, data.cos_lng, data.sin_lng]);

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

        $http.get(CONFIG.APIURL + 'fueltype', {skipAuthorization: true})
            //$http.get('fuel_types.json')
            .then(function (res) {

                var d = new Date();
                var n = d.getTime();

                var datos = res.data;

                db.transaction(function (tx) {

                    tx.executeSql('CREATE TABLE IF NOT EXISTS fueltype(idfueltype integer primary key, fueltype, fuelcode,date)', [], function (tx, res) {

                        for (var i in datos) {
                            var data = datos[i];
                            tx.executeSql("select idfueltype from fueltype where idfueltype=" + data.idfueltype, [], (function (data) {
                                    return function (tx, res) //success function
                                        {
                                            if (res.rows.length > 1) {
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

        $http.get(CONFIG.APIURL + 'price?' + param, {skipAuthorization: true})
            //$http.get('stations_price.json')

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
    
    return {
        inicializeTables: inicializeTables,
        updateStations: updateStations,
        updatePrices: updatePrices,
        updateFueldType: updateFueldType,
    }
})
;