#Console

To get ES gas stations
-------------------------
	./open_gasolineras.py -d /some/path/openyapi/data/officialstations/es/  
	./open_gasolineras.py -d ////openyapi/data/officialstations/es/

To import stations and prices
-----------------------------
	php public/index.php import gasstation All

To import per filename
----------------------
<BIE.json>  
<BIO.json>  
<G95.json>  
<G98.json>  
<GLP.json>  
<GNC.json>  
<GOA.json>  
<GOB.json>  
<GOC.json>  
<GPR.json>  
<NGO.json>  

	php public/index.php import gasstation <filename.json>

To call api from console
------------------------
	curl -s -H "Accept: application/vnd.openy.v1+json" http:///station | python -mjson.tool  
	
__Output to file__

	curl -s -H "Accept: application/vnd.openy.v1+json" http:///station?psize=all | python -mjson.tool > stations.json


Database users for API
------------------------
1. Use md5 crypt
2. To crypt:  

	php -r 'echo md5("password") . "\n";'

   
Get GasStation and Price from REST MINETUR
------------------------------------------
	curl -s -H "Content-type: text/plain; charset=utf-8" https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/ | python -mjson.tool > precios-off3.json
	 
 	curl -s -H "Content-type: text/plain; charset=utf-8" https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/PostesMaritimos/ | python -mjson.tool > preciosMar-off3.json 

Change encoding
---------------
	python mjson2.tool 
	
Import Address
---------------
	php public/index.php import address
	
Import Locality
---------------
	php public/index.php import locality
	
Update GasStation Info
----------------------

Import gasstation info into precios-off3.json
	
	curl -s -H "Content-type: text/plain; charset=utf-8" https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/ | python -mjson.tool > precios-off3.json
	
Change encoding with mjson2.tool. This only works from precios-off3.json and exit a precios-off33.json
	
	python ../../bin/mjson2.tool  

Import municipality
	
	php public/index.php import municipality
		
Import locality 

	php public/index.php import locality
	
Import gas station address 

	php public/index.php import address
	
Update prices

	php public/index.php import gasstation All

Cron to import and update prices DAILY 
--------------------------------------

	1. Get station price
	php public/index.php getminetur GasStationPrice
	
	2. Import station price
	php public/index.php import gasstation All
	
Cron to import and update address WEEKLY 
----------------------------------------
 	
 	1. Get station address
	php public/index.php getminetur EstacionesTerrestres
	
	2. Import station address
	php public/index.php import address
	
Cron to import and update address MONTHLY 
-----------------------------------------
 	
 	1. Get marinepost address
	php public/index.php getminetur PostesMaritimos
	
Cron examples
-------------
	php /var/www/vhosts/.com/subdomains/openyapi/public/index.php getminetur GasStationPrice
	php /var/www/vhosts/.com/subdomains/openyapi/public/index.php import gasstation All
	
	php /var/www/vhosts/.com/subdomains/openyapi/public/index.php getminetur EstacionesTerrestres
	php /var/www/vhosts/.com/subdomains/openyapi/public/index.php import address
		