<?php
/**
 * Fuelplus Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(
	/**
     * Domain name
     */
    'domain_name'   => '',
    'dn_uuid'       => 'my_uuid', // Generated with Uuid::uuid5(Uuid::NAMESPACE_DNS, $domain_name);

    'smtp_options' => array('name' => 'Openy App',
                                    'host' => '.com',
                                    'connection_class' => 'login',
                                    'connection_config' => array(
                                        'username' => 'openy@.com',
                                        'password' => 'pass',
                                    )),

    'email_receivers' => array(
                            'contact@openy.es'    => '@gmail.com',
                            'feedback@openy.es'   => '@gmail.com',
                            'pos@openy.es'        => '@gmail.com',
                            'dev@openy.es'        => '@gmail.com'
    ),

    'json_collection_per_page_items' => 30,
    'installation_date' => '2015-06-19',
    'is_enable_authentication_header' => false,

    'payment' => [
        'methods' => array(
            'default' => 'creditcard',
            'creditcard' => 1,
            'paypal' => 2,
            'voucher'=> 3,
            'credits'=> 4,
            ),
        'taxes' => array(
            'IVA_21' => ['name'=>'IVA','locale'=>'es_ES','percent'=>'21'],
            'IVA_10' => ['name'=>'IVA','locale'=>'es_ES','percent'=>'10'],
            'IVA_4' => ['name'=>'IVA','locale'=>'es_ES','percent'=>'4'],
            'IGIC_7' => ['name'=>'IGIC','locale'=>'es_ES','percent'=>'7'],
            ),
        'errors' => [
            'receipt' => [
                'NUMBER_REPEATED' => [
                    'text'                      => 'Ya existe otro recibo con este número',
                    'translations' => ['es_ES'  => 'Ya existe otro recibo con este número']
                ],
                'WRONG_RECEIPT_NUMBER' => [
                    'text'                      => 'Número de recibo incorrecto',
                    'translations' => ['es_ES'  => 'Número de recibo incorrecto']
                ],
                'RECEIPT_ALREADY_FOUND' => [
                    'text'                      => 'Ya existe otro recibo emitido para este pago',
                    'translations' => ['es_ES'  => 'Ya existe otro recibo emitido para este pago']
                ],
                'RECEIPT_NOT_FOUND' => [
                    'text'                      => 'No se ha emitido ningún recibo para este pago',
                    'translations' => ['es_ES'  => 'No se ha emitido ningún recibo para este pago']
                ],

            ],
            'payment' => [
            ],
            'order' => [
            ]
        ],
        'policies' => [
            'payment' => [
                'auto_receipt' => TRUE,
            ]

        ]
    ],

    'creditcard' => array(
        'limits' => array(
            'register' => array(
                'similar_cards' => array(
                    'validated' => FALSE,    //FALSE or NULL Must be interpreted as no limit stablished
                    'non_validated' => 2,
                ),
                'single_cards' => FALSE,    //FALSE or NULL Must be interpreted as no limit stablished
            ),
            'validation' => 3,  // 3 trials is the maximum number of attempts for validating a card
        ),
        'policies' => [
            'register' => [
                'favorite_first' => TRUE,
            ],
            'validation' => [
                'auto_activate' => TRUE,
                'favorite_last' => FALSE,
            ]
        ]
    ),


    'max_number_of_pins_tries'    => 4,
    'days_to_reset_pins_tries'    => 1209600,    //  // 14 days to reset in seconds
    'max_number_of_antifraud_sms' => 3,
    'is_antifraud_verification_required' => true,

    'is_enable_verify_with_sms_send_email'              => false,
    'sms_url' => 'https://rest.messagebird.com/',
    'sms_resources' => array ('messages' => array('endpoint'=>'messages',
                            'collection'=>'/%s',
                            'headers'=>array('Accept' => 'application/json',
                                'Content-Type'=>'application/json',
                                'Authorization'=>'AccessKey keykey')
    )),
    'monitors_url' => 'http:///opystation/',
    'refuel_monitors'=>array ('raisepump' => array('endpoint'=>'monitorraisepump',
                                    'headers'=>array('Accept' => 'application/json',
                                        'Content-Type'=>'application/json',
                                        'Authorization'=>'Basic keykey')
                                ),
                                'hangpump' => array('endpoint'=>'monitorhangpump',
                                    'headers'=>array('Accept' => 'application/json',
                                        'Content-Type'=>'application/json',
                                        'Authorization'=>'Basic keykey=')
                                ),

    ),

/**
 * End of ZfcUser configuration
 */
);

/**
 * You do not need to edit below this line
 */
return array(
		'openy' => $settings,
);
