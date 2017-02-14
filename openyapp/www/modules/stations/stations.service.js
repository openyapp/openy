var stationsService = function stationsService($q, $http, store, CONFIG){


    var loadStations = function (data){
        var openy = "";
        var favorite = "";
        var northeast = data.position.northeast.split(",");
        var southwest = data.position.southwest.split(",");
        console.log(northeast+'->'+southwest);
        //window.sqlitePlugin.deleteDatabase({name: "openy.db", location: 1}, successcb, errorCB);
       //var db = window.sqlitePlugin.openDatabase({name: "openy.db", createFromLocation: 1});
        var deferred = $q.defer();
        var result = [];

        if( northeast[1] <= southwest[1])
        {
            var lng = northeast[1];
            var lng2 = southwest[1];
        }else{
            var lng = southwest[1];
            var lng2 = northeast[1];
        }

        if( northeast[0] <= southwest[0])
        {
            var lat = northeast[0];
            var lat2 = southwest[0];
        }else{
            var lat = southwest[0];
            var lat2 = northeast[0];
        }

        if(data.openy) openy = " and openy='"+data.openy+"' ";
        if(data.favorites) favorite = " and favorite=1 ";

        db.transaction(function(tx) {
            tx.executeSql("select * from stations  inner join prices on prices.idoffstation = stations.idoffstation inner join fueltype on prices.idfueltype = fueltype.idfueltype where fueltype.fuelcode='"+data.fuelcode+"' and lat between '"+lat+"' and '"+lat2+"' and lng between '"+lng+"' and '"+lng2+"'"+openy+favorite+" limit 50", [], function(tx, res)
            {
                for(var i = 0; i < res.rows.length; i++)
                {
                    var station = {};
                    station.idstation = res.rows.item(i).idoffstation;
                    station.lat = res.rows.item(i).lat;
                    station.lng = res.rows.item(i).lng;
                    station.price = res.rows.item(i).price;
                    station.name = res.rows.item(i).name;
                    station.logo = res.rows.item(i).logo;
                    station.openy = res.rows.item(i).openy;
                    station.favorite = res.rows.item(i).favorite;
                    station.address = res.rows.item(i).address;
                    
                    result.push(station);
                }
                console.log(result);
                deferred.resolve(result);

            }, function(e) {

            deferred.reject('El servicio  de mapas no está disponible');

            });
        });
        return deferred.promise;
    };

    var getStations = function (data){
        var deferred = $q.defer();
        if (!data.position) {
            deferred.reject('La posición no está disponible');
           return deferred.promise;
        }
        var result = [];
        var distance = "";
        var fueltype = "";
        var idstation = "";
        var extraorder = "";
        var recommended = "";
        var openy = "";
        var favorites = "";


        Math.radians = function(degrees) {
          return degrees * Math.PI / 180;
        };

        // Converts from radians to degrees.
        Math.degrees = function(radians) {
          return radians * 180 / Math.PI;
        };

        var CUR_cos_lat = Math.cos(Math.radians(data.position.lat));
        var CUR_cos_lng = Math.cos(Math.radians(data.position.lng));
        var CUR_sin_lat = Math.sin(Math.radians(data.position.lat));
        var CUR_sin_lng = Math.sin(Math.radians(data.position.lng));

         distance = ",("+CUR_sin_lat+" * sin_lat + "+CUR_cos_lat+" * cos_lat * ( "+CUR_sin_lng+" * sin_lng + "+CUR_cos_lng+" * cos_lng)) as distance ";
        var maxDistance = Math.cos(parseInt(data.radioAction) / 6371);//2km


        extraorder = ", distance DESC";

        if(!data.direction) data.direction="ASC";

        if(data.field == "distance" || data.field=="price")
        {



            if(data.field =="price")
            {
                data.direction = "ASC";
                extraorder = ", distance DESC";
            }else{
                data.direction = "DESC";
            }

            console.log(data);

        }





        if(data.fuelcode)
        {
            fueltype = " and fueltype.fuelcode='"+data.fuelcode+"' ";
        }

        if(data.idstation)
        {
            idstation = " and stations.idoffstation="+data.idstation+" ";
        }



        if(data.filter)
        {
            if(data.filter=="openy"){

                openy = " and openy='1'";

            }

            if(data.filter=="favorites"){

                favorites = " and favorite=1 ";

            }
        }
        console.log("select * "+distance+" from stations inner join prices on prices.idoffstation = stations.idoffstation inner join fueltype on prices.idfueltype = fueltype.idfueltype  where 1=1 and distance > "+maxDistance+" "+fueltype+idstation+recommended+openy+favorites+" order by "+data.field+" "+data.direction+ extraorder+" group by stations.idoffstation limit "+data.page+",10 ");


        db.transaction(function(tx) {
            tx.executeSql("select * "+distance+" from stations inner join prices on prices.idoffstation = stations.idoffstation inner join fueltype on prices.idfueltype = fueltype.idfueltype  where 1=1 and distance > "+maxDistance+" "+fueltype+idstation+recommended+openy+favorites+" group by stations.idoffstation order by "+data.field+" "+data.direction+ extraorder+" limit "+data.page+",10 ", [], function(tx, res)
            {
                for(var i = 0; i < res.rows.length; i++)
                {
                    var station = {};
                    station.idstation = res.rows.item(i).idoffstation;
                    station.lat = res.rows.item(i).lat;
                    station.lng = res.rows.item(i).lng;
                    station.price = res.rows.item(i).price;
                    station.name = res.rows.item(i).name;
                    station.logo = res.rows.item(i).logo;
                    station.openy = res.rows.item(i).openy;
                    station.favorite = res.rows.item(i).favorite;
                    station.address = res.rows.item(i).address;
                    station.fueltype = res.rows.item(i).fueltype;
                    station.fuelcode = res.rows.item(i).fuelcode;
                    station.distance = res.rows.item(i).distance;
                    
                    result.push(station)
                }
                deferred.resolve(result);

            }, function(e) {

            deferred.reject('El servicio  getStations no está disponible');

            });
        },function(err){console.log(err)});
        return deferred.promise;
    };


    var getStationFuel = function (idoffstation){
        var deferred = $q.defer();
        var result = [];

        db.transaction(function(tx) {

            console.log("select * from  prices  inner join fueltype on prices.idfueltype = fueltype.idfueltype where prices.idoffstation="+idoffstation);
            tx.executeSql("select * from  prices  inner join fueltype on prices.idfueltype = fueltype.idfueltype where prices.idoffstation="+idoffstation, [], function(tx, res)
            {
                console.log(res);
                for(var i = 0; i < res.rows.length; i++)
                {
                    result.push({ price:res.rows.item(i).price,fueltype:res.rows.item(i).fueltype,fuelcode:res.rows.item(i).fuelcode})
                }
                deferred.resolve(result);

            }, function(e) {

            deferred.reject('El servicio  getStationFuel no está disponible');

            });
        },function(err){console.log(err)});
        return deferred.promise;
    };

        var getStation = function (idoffstation){
        console.log('dentro de funcion',data);
        var deferred = $q.defer();
        var result = [];

        db.transaction(function(tx) {

            tx.executeSql("select *  from  stations  where idoffstation="+idoffstation, [], function(tx, res)
            {
                console.log(res);
                for(var i = 0; i < res.rows.length; i++)
                {
                    var station = {};
                    station.idstation = res.rows.item(i).idoffstation;
                    station.lat = res.rows.item(i).lat;
                    station.lng = res.rows.item(i).lng;
                    station.name = res.rows.item(i).name;
                    station.logo = res.rows.item(i).logo;
                    station.openy = res.rows.item(i).openy;
                    station.favorite = res.rows.item(i).favorite;
                    station.address = res.rows.item(i).address;
                    station.distance = res.rows.item(i).distance;
                    
                    
                    result.push(station)
                }
                deferred.resolve(result);

            }, function(e) {

            deferred.reject('El servicio  getStationFuel no está disponible');

            });
        },function(err){console.log(err)});
        return deferred.promise;
    };

     var getFuelType = function (){
        var deferred = $q.defer();
        var result = [];

        db.transaction(function(tx) {

            tx.executeSql("select *  from  fueltype", [], function(tx, res)
            {
                console.log(res);
                for(var i = 0; i < res.rows.length; i++)
                {
                    result.push({fueltype : res.rows.item(i).fueltype, fuelcode : res.rows.item(i).fuelcode})
                }
                deferred.resolve(result);

            }, function(e) {

            deferred.reject('El servicio  getFuelType no está disponible');

            });
        },function(err){console.log(err)});
        return deferred.promise;
    };

    var addFavorite = function(idoffstation){
        var favorites = store.get('favorites');
        console.log(favorites);
        if(!favorites) favorites = [];
        var d = new Date();
        var n = d.getTime();


        var deferred,
            //  console.log(device);
            deferred = $q.defer();

            $http({
                method: 'POST',
                url: CONFIG.APIURL + '/favoritestation',
                data: {
                    idoffstation : idoffstation,
                },
                cache : false,
                headers: {'Content-Type': 'application/json'}
            }).then(function (res) {
                console.log(res);
                if (res.status === 201) {
                    deferred.resolve();
                } else {
                    deferred.reject();
                }
            }, function (error) {
                console.log(error);
                if (error.data && error.data.code.error) {
                    deferred.reject(error.data.code.error);
                } else {
                    deferred.reject('El servicio no está disponible');
                }
            });



            db.transaction(function(tx)
            {
                tx.executeSql('UPDATE stations set favorite='+1+' where idoffstation=' + idoffstation);
                
            });

        return deferred.promise;

    }

    var removeFavorite = function(idoffstation){
        var favorites = store.get('favorites');
        if(!favorites) favorites = [];
        var d = new Date();
        var n = d.getTime();
        console.log(favorites);


         var deferred,
            //  console.log(device);
            deferred = $q.defer();

            $http({
                method: 'DELETE',
                skipAuthorization: true,//no queremos enviar el token en esta petición
                url: CONFIG.APIURL + '/favoritestation',
                data: {
                    idoffstation : idoffstation,
                },
                cache : false,
                headers: {'Content-Type': 'application/json'}
            }).then(function (res) {
                console.log(res);
                if (res.status === 201) {
                    deferred.resolve();
                } else {
                    deferred.reject();
                }
            }, function (error) {
                console.log(error);
                if (error.data && error.data.code.error) {
                    deferred.reject(error.data.code.error);
                } else {
                    deferred.reject('El servicio no está disponible');
                }
            });



        db.transaction(function(tx)
        {
            tx.executeSql('UPDATE stations set favorite='+0+' where idoffstation=' + idoffstation);
        });

                return deferred.promise;


    }
    

    var getIcon = function(tipo){
        var local_icons_per = new Object();

            var icono         = new Object();
            icono.iconUrl     = 'lib/maps/iconos/'+tipo+'.png';
            icono.iconSize    = [30, 45];
            icono.iconAnchor  = [30, 45];
            icono.popupAnchor = [1, -34];
            icono.shadowSize  = [45, 45];

            local_icons_per[tipo] = 'www/lib/maps/iconos/'+tipo+'.png';

                return  local_icons_per;
    };

    var orderBy = function(property)
    {
        var sortOrder = 1;
        if(property[0] === "-") {
            sortOrder = -1;
            property = property.substr(1);
        }
        return function (a,b) {
            var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
            return result * sortOrder;
        }

    }


return {
        loadStations: loadStations,
        getStations: getStations,
        getStation: getStation,
        getStationFuel : getStationFuel,
        getFuelType : getFuelType,
        addFavorite : addFavorite,
        removeFavorite : removeFavorite,
        //distance: distance,
        //getIcon:  getIcon,
        //orderBy:  orderBy,
    }
}

angular.module('starter')
    .factory('stationsService', stationsService);