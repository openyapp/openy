<?php
/**
 * Opypos Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(

    'raise_pump_timeout'=>60,   // Secs
    'finish_refuel'=>300,       // Secs

    /**
     * posNetwork configuration
     *
     * opy_station => array(adapter, endpoint)
     */
	'pos_network' => array ('opy_1' => array('opy_station' => '1',
                                            'adapter' => 'aadapter',
                                            'endpoint' => 'http://your.server.com:60606/',
	                                        'files' => array('comandos'=>'trabajo/COMANDOS.DAT',
	                                                         'estados'=>'trabajo/ESTADO.DAT',
	                                                         'precios'=>'trabajo/Precios.dat'),
                                    	    'resources' => array (
                                                    	        'configuration' => array('endpoint'=>'configuration',
                                                    	            'collection'=>'/%s',
                                                    	            'headers'=>array('Accept' => 'application/json',
                                                    	                'Content-Type'=>'application/json',
                                                    	                'Authorization'=>'Basic b3Blbnk6b3B5XzE=')
                                                    	        ),
                                                    	        'sendorder' => array('endpoint'=>'sendorder',
                                                    	            'collection'=>'/%s',
                                                    	            'headers'=>array('Accept' => 'application/json',
                                                    	                'Content-Type'=>'application/json',
                                                    	                'Authorization'=>'Basic b3Blbnk6b3B5XzE=')
                                                    	        ),
                                                    	        'collect' => array('endpoint'=>'collect',
                                                    	            'collection'=>'/%s',
                                                    	            'headers'=>array('Accept' => 'application/json',
                                                    	                'Content-Type'=>'application/json',
                                                    	                'Authorization'=>'Basic b3Blbnk6b3B5XzE=')
                                                    	        ),

                                            ),

                            )
	),

    'androidPush' => array('endpoint'=> 'https://android.googleapis.com/gcm/send',
                            'headers'=>array('Accept' => 'application/json',
                                'Content-Type'=>'application/json',
                                'Authorization'=>'key=AIzaSyDi6_XwWXXVNAQRVQ2hVUg1KfobHfcthfs')
    ),

    'max_distace'=>50,



/**
 * End of Opypos configuration
 */
);

/**
 * You do not need to edit below this line
 */
return array(
		'opypos' => $settings,
);
