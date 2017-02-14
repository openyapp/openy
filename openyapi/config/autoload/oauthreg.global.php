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

    'smtp_options' => array('name' => 'sitename.com',
                                    'host' => 'smtp.gmail.com',
                                    'connection_class' => 'login',
                                    'port' => '465',
                                    'connection_config' => array(
                                        'ssl' => 'ssl',
                                        'username' => 'user@example.com',
                                        'password' => 'pass',
                                    )),

    'verification_front_endpoint'   => "http:///verifyemail/",
    'new_psassword_front_endpoint'  => "http:///verifyrecoverpassword/",
    'allways_authorized_routes'     => array('oauthreg.rest.clientregister'),

    'password_cost'                 => 14,
    'max_number_of_sms'             => 3,
    'is_enable_xapikey_header'      => false,

    'is_enable_verify_email'                            => true,
    'is_enable_verify_with_sms'                         => false,
    'is_enable_verify_with_sms_send_email'              => false,

    'is_enable_autoverify_user'                         => false,
    'is_enable_delete_temporal_info_after_verification' => false,

    'sms_url' => 'https://rest.messagebird.com/',
    'sms_resources' => array ('messages' => array('endpoint'=>'messages',
                                                  'collection'=>'/%s',
                                                  'headers'=>array('Accept' => 'application/json',
                                                                          'Content-Type'=>'application/json',
                                                                          'Authorization'=>'AccessKey ARSVabwudlWy4Cw0lHocWN65B')
    )),

    'sent_recover_password_to_email'    => true,
    'sent_recover_password_link_email'  => false,
    //'force_ssl_modules' => array('Oauthreg'),
	'force_ssl_modules' => array(),

/**
 * End of ZfcUser configuration
 */
);

/**
 * You do not need to edit below this line
 */
return array(
		'oauthreg' => $settings,
);
