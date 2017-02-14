<?php
return array(
    array(
    	'label' => 'Developers',
    	'route' => 'developer',
    	'pages' => array(
    	        array(
        	    		'label' => 'Developer',
        	    		'route' => 'developer',
        	    		'action' => 'index',
        	    ),


    			array(
    					'label' => 'Debug',
    					'route' => 'debug',
    					'action' => 'index',
    					'pages' => array(
    							array(
    									'label' => 'All Routes',
    									'route' => 'debug',
    									'action' => 'allroutes',
    							),
    					),
    			),
    	    	array(
    	    		'label' => 'Docs',
    	    		'route' => 'markdown',
    	    		'action' => 'index',
    	    	    'order' => 100,
    	    	    'pages' => array(
    	    	    		array(
    	    	    				'label' => 'About this',
    	    	    				'route' => 'markdown',
    	    	    				'params'     => array('filename' => 'do'),
    	    	    				'pages' => array(
    	    	    						array(
    					    	    				'label' => 'Markdown Syntax',
    					    	    				'route' => 'markdown',
    					    	    				'params'     => array('filename' => 'markdown-syntax')
    					    	    		),
    	    	    				),
    	    	    		),
    	    	    		array(
    	    	    				'label' => 'Confs & Installs',
    	    	    				'route' => 'markdown',
    	    	    				'params'     => array('filename' => 'install-and-confs'),
    	    	    				'pages' => array(
    	    	    						array(
    					    	    				'label' => 'Social',
    					    	    				'route' => 'markdown',
    					    	    				'params'     => array('filename' => 'social')
    					    	    		),
    					    	    		array(
    					    	    				'label' => 'Bower',
    					    	    				'route' => 'markdown',
    					    	    				'params'     => array('filename' => 'bower')
    					    	    		),
    	    	    						array(
    	    	    								'label' => 'Erratas',
    	    	    								'route' => 'markdown',
    	    	    								'params'     => array('filename' => 'erratas')
    	    	    						),


    	    	    				),
    	    	    		),
    	    	        array(
    	    	            'label' => 'Git',
    	    	            'route' => 'markdown',
    	    	            'params'     => array('filename' => 'git'),
    	    	            'pages' => array(
    	    	                array(
    	    	                    'label' => 'Using Git',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('filename' => 'git')
    	    	                ),
    	    	            ),
    	    	        ),
    	    	        array(
    	    	            'label' => 'ZF2 Stuf',
    	    	            'route' => 'markdown',
    	    	            'params'     => array('filename' => 'zf2stuff'),
    	    	            'pages' => array(
    	    	                array(
    	    	                    'label' => 'Db Query\'s',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('filename' => 'db-query')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Get Params',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('filename' => 'zf2-params')
    	    	                ),
    	    	            ),
    	    	        ),
    	    	        array(
    	    	            'label' => 'Test',
    	    	            'route' => 'markdown',
    	    	            'params'     => array('filename' => 'test'),
    	    	            'pages' => array(
    	    	                array(
    	    	                    'label' => 'PHPUnit test console',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'test','filename' => 'console')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Codeception',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'test','filename' => 'codeception')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Test POS',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'test','filename' => 'test-pos')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Test PUSH Android',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'test','filename' => 'test-pushandroid')
    	    	                ),
    	    	            ),
    	    	        ),


    	    	    		array(
    	    	    				'label' => 'Apigility',
    	    	    				'route' => 'markdown',
    	    	    				'params'     => array('filename' => 'api-intro'),
    	    	    				'pages' => array(
        	    	    				    array(
        	    	    				        'label' => 'Create RPC service',
        	    	    				        'route' => 'markdown',
        	    	    				        'params'     => array('filename' => 'api-createrpc')
        	    	    				    ),
    	    	    						array(
    	    	    								'label' => 'Api Considerations',
    	    	    								'route' => 'markdown',
    	    	    								'params'     => array('filename' => 'api-considerations')
    	    	    						),
    	    	    						array(
    	    	    								'label' => 'Apigility',
    	    	    								'route' => 'markdown',
    	    	    								'params'     => array('filename' => 'apigility')
    	    	    						),

        	    	    				    array(
        	    	    				    		'label' => 'Apigility Coding Create',
        	    	    				    		'route' => 'markdown',
        	    	    				    		'params'     => array('filename' => 'apigilitycoding')
        	    	    				    ),
        	    	    				    array(
        	    	    				        'label' => 'Redirect Resource',
        	    	    				        'route' => 'markdown',
        	    	    				        'params'     => array('filename' => 'apigility-redirect-resource')
        	    	    				    ),
        	    	    				    array(
        	    	    				        'label' => 'Resultset and hydrators',
        	    	    				        'route' => 'markdown',
        	    	    				        'params'     => array('filename' => 'apigility-resultset-hydrators')
        	    	    				    ),
    	    	    				),
    	    	    		),
    	    	        array(
    	    	            'label' => 'Openy POS',
    	    	            'route' => 'markdown',
    	    	            'params'     => array('filename' => 'pos'),
    	    	            'pages' => array(
    	    	                array(
    	    	                    'label' => 'Console',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'pos','filename' => 'console')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Database Query\'s',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'pos','filename' => 'querys')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'POS Offline Documentation',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'pos','filename' => 'doc_exchanged')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'POS Interfaces',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'pos','filename' => 'interfaces')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'POS API aadapter',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'pos','filename' => 'aadapter_api')

    	    	                ),
    	    	                array(
    	    	                    'label' => 'POS Operation aadapter',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'pos','filename' => 'aadapter_opt')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'POS Documentation aadapter',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'pos','filename' => 'aadapter_doc')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'POS Install aadapter',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'pos','filename' => 'aadapter_install')
    	    	                ),
    	    	            ),
    	    	        ),
    	    	        array(
    	    	            'label' => 'On Server Install',
    	    	            'route' => 'markdown',
    	    	            'params'     => array('namespace'=>'server', 'filename' => 'server'),
    	    	            'pages' => array(
    	    	                array(
    	    	                    'label' => 'New POS',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'server','filename' => 'newpos')
    	    	                ),
    	    	            ),
    	    	        ),
    	    	        array(
    	    	            'label' => 'Monitor',
    	    	            'route' => 'markdown',
    	    	            'params'     => array('filename' => 'monitor'),
    	    	            'pages' => array(
    	    	                array(
    	    	                    'label' => 'Work with Monitor',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'monitor','filename' => 'work')
    	    	                ),
	    	                ),
    	                ),
    	                array(
    	    	            'label' => 'Api',
    	    	            'route' => 'markdown',
    	    	            'params'     => array('namespace'=>'api','filename' => 'intro'),
    	    	            'pages' => array(

    	    	                array(
    	    	                    'label' => 'Install',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'install')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Console',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'console')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Endpoints',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'index')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Client register',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'clientregister')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'User login',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'userlogin')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'User info',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'userinfo')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Preference',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'preferences')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'SMS',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'sms')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Auth call',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'authcall')
    	    	                ),
                                array(
                                    'label' => 'Credit Card Register',
                                    'route' => 'markdown',
                                    'params'     => array('namespace'=>'api','filename' => 'creditcard_register')
                                ),
                                array(
                                    'label' => 'Credit Card CRUD',
                                    'route' => 'markdown',
                                    'params'     => array('namespace'=>'api','filename' => 'creditcard_crud')
                                ),
    	    	                array(
    	    	                    'label' => 'Transaction',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'transaction')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Station',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'station')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'List Stations',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'liststations')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Favorite Station',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'favoritestation')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Price',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'price')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Fueltype',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'fueltype')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Send Feedback',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'sendfeedback')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'POS Ping',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'pos_ping')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'POS Closest',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'pos_closest')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'POS Configuration',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'pos_configuration')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'POS Prices',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'pos_price')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'POS Pump',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'pos_pump')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'POS Pump status',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'pos_pumpstatus')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Verify Pin',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'verify_pin')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Orders',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'orders')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Refuel',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'refuel')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Collect',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'collect')
    	    	                ),
    	    	                array(
    	    	                    'label' => 'Push',
    	    	                    'route' => 'markdown',
    	    	                    'params'     => array('namespace'=>'api','filename' => 'push')
    	    	                ),
                                array(
                                    'label' => 'Receipts',
                                    'route' => 'markdown',
                                    'params'     => array('namespace'=>'api','filename' => 'receipts')
                                ),
                                array(
                                    'label' => 'Invoices',
                                    'route' => 'markdown',
                                    'params'     => array('namespace'=>'api','filename' => 'invoices')
                                ),

    	    	            ),
    	    	        ),


	    	    ),
	    	),
    	),
	),

);
