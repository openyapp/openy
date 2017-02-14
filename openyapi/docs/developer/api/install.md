#Console

Install stuff
-------------

Application.config.php
----------------------

The path of config files need to be modifyed to your working enviroment
		
	'config_glob_paths' => array(
		'////openyapi/config/autoload/{,*.}{global,local}.php',
	    '/var/www/vhosts/.com/subdomains/openyapi/config/autoload/{,*.}{global,local}.php',
	),
        
Authorization Header
--------------------

Some how your webserver is not passing the Authorization header, so you got FORBIDDEN always.

Se the following [link to discussion](https://groups.google.com/a/zend.com/forum/#!msg/apigility-users/iYQnppJyYho/oDm0eMo6xLQJ)

__The fix:__ 

Add this to the `.htaccess`

	RewriteCond %{HTTP:Authorization}  ^(.*)
	RewriteRule ^(.*)$ $1 [e=HTTP_AUTHORIZATION:%1]


or Add the following to your `vhost definition`: 

	SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1 

To __track or check the error__ just make in the __invoke method of `/vendor/zfcampus/zf-mvc-auth/src/Authentication/DefaultAuthenticationListener.php`  
 
	print_r($request->getHeaders()); 


CER and SSL issue
-----------------
	
	SSL certificate: unable to get local issuer certificate  
	
	
To fix this issue 


Download cacert.pem [link](https://support.zend.com/hc/en-us/articles/204159368-PHP-CURL-HTTPS-Error-SSL-certificate-problem-unable-to-get-local-issuer-certificate-)

Set cacert into

	Directory /usr/local/zend/apache2/conf
	or
	Directory /usr/local/zend/etc/ssl
	with 
	g+r o+r u+rw

Edit `php.ini` and add the path to:
	
	[curl]
		; A default value for the CURLOPT_CAINFO option. This is required to be an
		; absolute path.
		curl.cainfo= /usr/local/zend/apache2/conf/cacert.pem
		
And change capath
	
	[openssl]
	; The location of a Certificate Authority (CA) file on the local filesystem
	; to use when verifying the identity of SSL/TLS peers. Most users should
	; not specify a value for this directive as PHP will attempt to use the
	; OS-managed cert stores in its absence. If specified, this value may still
	; be overridden on a per-stream basis via the "cafile" SSL stream context
	; option.
	openssl.cafile=/usr/local/zend/etc/ssl/certs/client-macpro.crt
	
	; If openssl.cafile is not specified or if the CA file is not found, the
	; directory pointed to by openssl.capath is searched for a suitable
	; certificate. This value must be a correctly hashed certificate directory.
	; Most users should not specify a value for this directive as PHP will
	; attempt to use the OS-managed cert stores in its absence. If specified,
	; this value may still be overridden on a per-stream basis via the "capath"
	; SSL stream context option.
	openssl.capath=/usr/local/zend/etc/ssl/certs	

ZEND MAIL could not open socket issue
-------------------------------------

Error: __Could not open socket in Zend Mail__
		
To fix this issue edit `php.ini` and add the path to:

	[openssl]
		openssl.cafile=/usr/local/zend/apache2/conf/cacert.pem		