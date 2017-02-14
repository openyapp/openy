<?php
return array(
    'router' => array(
        'routes' => array(
            'oauthreg.rest.register' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/register[/:register_id]',
                    'defaults' => array(
                        'controller' => 'Oauthreg\\V1\\Rest\\Register\\Controller',
                    ),
                ),
            ),
            'oauthreg.rpc.verify-email' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/verifyemail[/:token][/:email]',
                    'defaults' => array(
                        'controller' => 'Oauthreg\\V1\\Rpc\\VerifyEmail\\Controller',
                        'action' => 'verifyEmail',
                    ),
                ),
            ),
            'oauthreg.rest.oauthuser' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/oauthuser[/:oauthuser_id]',
                    'defaults' => array(
                        'controller' => 'Oauthreg\\V1\\Rest\\Oauthuser\\Controller',
                    ),
                ),
            ),
            'oauthreg.rest.recoverpassword' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/recoverpassword[/:recoverpassword_id]',
                    'defaults' => array(
                        'controller' => 'Oauthreg\\V1\\Rest\\Recoverpassword\\Controller',
                    ),
                ),
            ),
            'oauthreg.rpc.verify-recover-password' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/verifyrecoverpassword[/:token][/:email]',
                    'defaults' => array(
                        'controller' => 'Oauthreg\\V1\\Rpc\\VerifyRecoverPassword\\Controller',
                        'action' => 'verifyRecoverPassword',
                    ),
                ),
            ),
            'oauthreg.rpc.set-new-password' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/setnewpassword',
                    'defaults' => array(
                        'controller' => 'Oauthreg\\V1\\Rpc\\SetNewPassword\\Controller',
                        'action' => 'setNewPassword',
                    ),
                ),
            ),
            'oauthreg.rest.clientregister' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/clientregister[/:clientregister_id]',
                    'defaults' => array(
                        'controller' => 'Oauthreg\\V1\\Rest\\Clientregister\\Controller',
                    ),
                ),
            ),
            'oauthreg.rpc.verify-sms' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/verifysms[/:code][/:iduser]',
                    'defaults' => array(
                        'controller' => 'Oauthreg\\V1\\Rpc\\VerifySms\\Controller',
                        'action' => 'verifySms',
                    ),
                ),
            ),
            'oauthreg.rpc.send-code' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/sendcode',
                    'defaults' => array(
                        'controller' => 'Oauthreg\\V1\\Rpc\\SendCode\\Controller',
                        'action' => 'sendCode',
                    ),
                ),
            ),
            'oauthreg.rpc.revoke' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/revoke',
                    'defaults' => array(
                        'controller' => 'Oauthreg\\V1\\Rpc\\Revoke\\Controller',
                        'action' => 'revoke',
                    ),
                ),
            ),
            'oauthreg.rpc.send-code-new-phone' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/sendcodenewphone',
                    'defaults' => array(
                        'controller' => 'Oauthreg\\V1\\Rpc\\SendCodeNewPhone\\Controller',
                        'action' => 'sendCodeNewPhone',
                    ),
                ),
            ),
            'oauthreg.rpc.verify-sms-new-phone' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/verifysmsnewphone[/:code][/:iduser]',
                    'defaults' => array(
                        'controller' => 'Oauthreg\\V1\\Rpc\\VerifySmsNewPhone\\Controller',
                        'action' => 'verifySmsNewPhone',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'oauthreg.rest.register',
            1 => 'oauthreg.rpc.verify-email',
            2 => 'oauthreg.rest.oauthuser',
            3 => 'oauthreg.rest.recoverpassword',
            4 => 'oauthreg.rpc.verify-recover-password',
            5 => 'oauthreg.rpc.set-new-password',
            6 => 'oauthreg.rest.clientregister',
            7 => 'oauthreg.rpc.verify-sms',
            8 => 'oauthreg.rpc.send-code',
            9 => 'oauthreg.rpc.revoke',
            10 => 'oauthreg.rpc.send-code-new-phone',
            11 => 'oauthreg.rpc.verify-sms-new-phone',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Oauthreg\\V1\\Rest\\Register\\RegisterResource' => 'Oauthreg\\V1\\Rest\\Register\\RegisterResourceFactory',
            'Oauthreg\\V1\\Rest\\Oauthuser\\OauthuserResource' => 'Oauthreg\\V1\\Rest\\Oauthuser\\OauthuserResourceFactory',
            'Oauthreg\\V1\\Rest\\Recoverpassword\\RecoverpasswordResource' => 'Oauthreg\\V1\\Rest\\Recoverpassword\\RecoverpasswordResourceFactory',
            'Oauthreg\\V1\\Rest\\Clientregister\\ClientregisterResource' => 'Oauthreg\\V1\\Rest\\Clientregister\\ClientregisterResourceFactory',
        ),
    ),
    'zf-rest' => array(
        'Oauthreg\\V1\\Rest\\Register\\Controller' => array(
            'listener' => 'Oauthreg\\V1\\Rest\\Register\\RegisterResource',
            'route_name' => 'oauthreg.rest.register',
            'route_identifier_name' => 'register_id',
            'collection_name' => 'registers',
            'entity_http_methods' => array(
                0 => 'GET',
            ),
            'collection_http_methods' => array(
                0 => 'POST',
            ),
            'collection_query_whitelist' => array(
                0 => 'email',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Oauthreg\\V1\\Rest\\Register\\RegisterEntity',
            'collection_class' => 'Oauthreg\\V1\\Rest\\Register\\RegisterCollection',
            'service_name' => 'Register',
        ),
        'Oauthreg\\V1\\Rest\\Oauthuser\\Controller' => array(
            'listener' => 'Oauthreg\\V1\\Rest\\Oauthuser\\OauthuserResource',
            'route_name' => 'oauthreg.rest.oauthuser',
            'route_identifier_name' => 'oauthuser_id',
            'collection_name' => 'oauthuser',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
            ),
            'collection_query_whitelist' => array(
                0 => 'username',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Oauthreg\\V1\\Rest\\Oauthuser\\OauthuserEntity',
            'collection_class' => 'Oauthreg\\V1\\Rest\\Oauthuser\\OauthuserCollection',
            'service_name' => 'Oauthuser',
        ),
        'Oauthreg\\V1\\Rest\\Recoverpassword\\Controller' => array(
            'listener' => 'Oauthreg\\V1\\Rest\\Recoverpassword\\RecoverpasswordResource',
            'route_name' => 'oauthreg.rest.recoverpassword',
            'route_identifier_name' => 'recoverpassword_id',
            'collection_name' => 'recoverpassword',
            'entity_http_methods' => array(),
            'collection_http_methods' => array(
                0 => 'POST',
            ),
            'collection_query_whitelist' => array(
                0 => 'email',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Oauthreg\\V1\\Rest\\Recoverpassword\\RecoverpasswordEntity',
            'collection_class' => 'Oauthreg\\V1\\Rest\\Recoverpassword\\RecoverpasswordCollection',
            'service_name' => 'Recoverpassword',
        ),
        'Oauthreg\\V1\\Rest\\Clientregister\\Controller' => array(
            'listener' => 'Oauthreg\\V1\\Rest\\Clientregister\\ClientregisterResource',
            'route_name' => 'oauthreg.rest.clientregister',
            'route_identifier_name' => 'clientregister_id',
            'collection_name' => 'clientregister',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Oauthreg\\V1\\Rest\\Clientregister\\ClientregisterEntity',
            'collection_class' => 'Oauthreg\\V1\\Rest\\Clientregister\\ClientregisterCollection',
            'service_name' => 'Clientregister',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Oauthreg\\V1\\Rest\\Register\\Controller' => 'HalJson',
            'Oauthreg\\V1\\Rpc\\VerifyEmail\\Controller' => 'Json',
            'Oauthreg\\V1\\Rest\\Oauthuser\\Controller' => 'HalJson',
            'Oauthreg\\V1\\Rest\\Recoverpassword\\Controller' => 'HalJson',
            'Oauthreg\\V1\\Rpc\\VerifyRecoverPassword\\Controller' => 'Json',
            'Oauthreg\\V1\\Rpc\\SetNewPassword\\Controller' => 'Json',
            'Oauthreg\\V1\\Rest\\Clientregister\\Controller' => 'HalJson',
            'Oauthreg\\V1\\Rpc\\VerifySms\\Controller' => 'Json',
            'Oauthreg\\V1\\Rpc\\SendCode\\Controller' => 'Json',
            'Oauthreg\\V1\\Rpc\\Revoke\\Controller' => 'Json',
            'Oauthreg\\V1\\Rpc\\SendCodeNewPhone\\Controller' => 'Json',
            'Oauthreg\\V1\\Rpc\\VerifySmsNewPhone\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'Oauthreg\\V1\\Rest\\Register\\Controller' => array(
                0 => 'application/hal+json',
                1 => 'application/json',
                2 => 'application/ork.register.v1+json',
            ),
            'Oauthreg\\V1\\Rpc\\VerifyEmail\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
                3 => 'application/ork.register.v1+json',
            ),
            'Oauthreg\\V1\\Rest\\Oauthuser\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
                3 => 'application/ork.oauthuser.v1+json',
            ),
            'Oauthreg\\V1\\Rest\\Recoverpassword\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
                3 => 'application/ork.recoverpassword.v1+json',
            ),
            'Oauthreg\\V1\\Rpc\\VerifyRecoverPassword\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
                3 => 'application/ork.verifyrecoverpassword.v1+json',
            ),
            'Oauthreg\\V1\\Rpc\\SetNewPassword\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
                3 => 'application/ork.setnewpassword.v1+json',
            ),
            'Oauthreg\\V1\\Rest\\Clientregister\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Oauthreg\\V1\\Rpc\\VerifySms\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Oauthreg\\V1\\Rpc\\SendCode\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Oauthreg\\V1\\Rpc\\Revoke\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Oauthreg\\V1\\Rpc\\SendCodeNewPhone\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Oauthreg\\V1\\Rpc\\VerifySmsNewPhone\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'Oauthreg\\V1\\Rest\\Register\\Controller' => array(
                0 => 'application/json',
                1 => 'application/ork.register.v1+json',
            ),
            'Oauthreg\\V1\\Rpc\\VerifyEmail\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
                2 => 'application/ork.register.v1+json',
            ),
            'Oauthreg\\V1\\Rest\\Oauthuser\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
            ),
            'Oauthreg\\V1\\Rest\\Recoverpassword\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
            ),
            'Oauthreg\\V1\\Rpc\\VerifyRecoverPassword\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
            ),
            'Oauthreg\\V1\\Rpc\\SetNewPassword\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
            ),
            'Oauthreg\\V1\\Rest\\Clientregister\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
            ),
            'Oauthreg\\V1\\Rpc\\VerifySms\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
            ),
            'Oauthreg\\V1\\Rpc\\SendCode\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
            ),
            'Oauthreg\\V1\\Rpc\\Revoke\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
            ),
            'Oauthreg\\V1\\Rpc\\SendCodeNewPhone\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
            ),
            'Oauthreg\\V1\\Rpc\\VerifySmsNewPhone\\Controller' => array(
                0 => 'application/vnd.oauthreg.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Oauthreg\\V1\\Rest\\Register\\RegisterEntity' => array(
                'entity_identifier_name' => 'email',
                'route_name' => 'oauthreg.rest.register',
                'route_identifier_name' => 'register_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Oauthreg\\V1\\Rest\\Register\\RegisterCollection' => array(
                'entity_identifier_name' => 'email',
                'route_name' => 'oauthreg.rest.register',
                'route_identifier_name' => 'register_id',
                'is_collection' => true,
            ),
            'Oauthreg\\V1\\Rest\\Oauthuser\\OauthuserEntity' => array(
                'entity_identifier_name' => 'iduser',
                'route_name' => 'oauthreg.rest.oauthuser',
                'route_identifier_name' => 'oauthuser_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Oauthreg\\V1\\Rest\\Oauthuser\\OauthuserCollection' => array(
                'entity_identifier_name' => 'iduser',
                'route_name' => 'oauthreg.rest.oauthuser',
                'route_identifier_name' => 'oauthuser_id',
                'is_collection' => true,
            ),
            'Oauthreg\\V1\\Rest\\Recoverpassword\\RecoverpasswordEntity' => array(
                'entity_identifier_name' => 'email',
                'route_name' => 'oauthreg.rest.recoverpassword',
                'route_identifier_name' => 'recoverpassword_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Oauthreg\\V1\\Rest\\Recoverpassword\\RecoverpasswordCollection' => array(
                'entity_identifier_name' => 'email',
                'route_name' => 'oauthreg.rest.recoverpassword',
                'route_identifier_name' => 'recoverpassword_id',
                'is_collection' => true,
            ),
            'Oauthreg\\V1\\Rest\\Clientregister\\ClientregisterEntity' => array(
                'entity_identifier_name' => 'privatekey',
                'route_name' => 'oauthreg.rest.clientregister',
                'route_identifier_name' => 'clientregister_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Oauthreg\\V1\\Rest\\Clientregister\\ClientregisterCollection' => array(
                'entity_identifier_name' => 'privatekey',
                'route_name' => 'oauthreg.rest.clientregister',
                'route_identifier_name' => 'clientregister_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-content-validation' => array(
        'Oauthreg\\V1\\Rest\\Register\\Controller' => array(
            'input_filter' => 'Oauthreg\\V1\\Rest\\Register\\Validator',
        ),
        'Oauthreg\\V1\\Rpc\\VerifyEmail\\Controller' => array(
            'input_filter' => 'Oauthreg\\V1\\Rpc\\VerifyEmail\\Validator',
        ),
        'Oauthreg\\V1\\Rest\\Oauthuser\\Controller' => array(
            'input_filter' => 'Oauthreg\\V1\\Rest\\Oauthuser\\Validator',
        ),
        'Oauthreg\\V1\\Rest\\Recoverpassword\\Controller' => array(
            'input_filter' => 'Oauthreg\\V1\\Rest\\Recoverpassword\\Validator',
        ),
        'Oauthreg\\V1\\Rpc\\VerifyRecoverPassword\\Controller' => array(
            'input_filter' => 'Oauthreg\\V1\\Rpc\\VerifyRecoverPassword\\Validator',
        ),
        'Oauthreg\\V1\\Rpc\\SetNewPassword\\Controller' => array(
            'input_filter' => 'Oauthreg\\V1\\Rpc\\SetNewPassword\\Validator',
        ),
        'Oauthreg\\V1\\Rest\\Clientregister\\Controller' => array(
            'input_filter' => 'Oauthreg\\V1\\Rest\\Clientregister\\Validator',
        ),
        'Oauthreg\\V1\\Rpc\\SendCode\\Controller' => array(
            'input_filter' => 'Oauthreg\\V1\\Rpc\\SendCode\\Validator',
        ),
        'Oauthreg\\V1\\Rpc\\SendCodeNewPhone\\Controller' => array(
            'input_filter' => 'Oauthreg\\V1\\Rpc\\SendCodeNewPhone\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'Oauthreg\\V1\\Rest\\Register\\Validator' => array(
            0 => array(
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                        'options' => array(),
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '128',
                            'min' => '6',
                        ),
                    ),
                ),
                'description' => 'User password',
            ),
            1 => array(
                'name' => 'first_name',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                        'options' => array(),
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '50',
                        ),
                    ),
                ),
                'description' => 'User first name',
            ),
            2 => array(
                'name' => 'last_name',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '50',
                        ),
                    ),
                ),
                'description' => 'User last name',
            ),
            3 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'phone_number',
            ),
            4 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\EmailAddress',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'email',
            ),
        ),
        'Oauthreg\\V1\\Rpc\\VerifyEmail\\Validator' => array(
            0 => array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\EmailAddress',
                        'options' => array(),
                    ),
                ),
                'description' => 'User\'s email verified',
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
        ),
        'Oauthreg\\V1\\Rest\\OauthUser\\Validator' => array(
            0 => array(
                'name' => 'password',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
            1 => array(
                'name' => 'first_name',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
            2 => array(
                'name' => 'last_name',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
            3 => array(
                'name' => 'username',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
        ),
        'Oauthreg\\V1\\Rest\\Oauthuser\\Validator' => array(
            0 => array(
                'name' => 'username',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            1 => array(
                'name' => 'password',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            2 => array(
                'name' => 'first_name',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            3 => array(
                'name' => 'last_name',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            4 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'phone_number',
            ),
            5 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'newpassword',
            ),
        ),
        'Oauthreg\\V1\\Rest\\Recoverpassword\\Validator' => array(),
        'Oauthreg\\V1\\Rpc\\SetNewPassword\\Validator' => array(
            0 => array(
                'name' => 'token',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            1 => array(
                'name' => 'email',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            2 => array(
                'name' => 'password',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
        ),
        'Oauthreg\\V1\\Rpc\\VerifyRevoverPassword\\Validator' => array(
            0 => array(
                'name' => 'email',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
        ),
        'Oauthreg\\V1\\Rpc\\VerifyRecoverPassword\\Validator' => array(
            0 => array(
                'name' => 'email',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
        ),
        'Oauthreg\\V1\\Rest\\Clientregister\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'osversion',
            ),
            1 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'publickey',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'lat',
            ),
            3 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'lng',
            ),
            4 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'other',
            ),
            5 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'privatekey',
            ),
            6 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'registerid',
            ),
        ),
        'Oauthreg\\V1\\Rpc\\SendCode\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'iduser',
            ),
        ),
        'Oauthreg\\V1\\Rpc\\SendCodeNewPhone\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'iduser',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'new_phone_number',
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Oauthreg\\V1\\Rpc\\VerifyEmail\\Controller' => 'Oauthreg\\V1\\Rpc\\VerifyEmail\\VerifyEmailControllerFactory',
            'Oauthreg\\V1\\Rpc\\VerifyRecoverPassword\\Controller' => 'Oauthreg\\V1\\Rpc\\VerifyRecoverPassword\\VerifyRecoverPasswordControllerFactory',
            'Oauthreg\\V1\\Rpc\\SetNewPassword\\Controller' => 'Oauthreg\\V1\\Rpc\\SetNewPassword\\SetNewPasswordControllerFactory',
            'Oauthreg\\V1\\Rpc\\VerifySms\\Controller' => 'Oauthreg\\V1\\Rpc\\VerifySms\\VerifySmsControllerFactory',
            'Oauthreg\\V1\\Rpc\\SendCode\\Controller' => 'Oauthreg\\V1\\Rpc\\SendCode\\SendCodeControllerFactory',
            'Oauthreg\\V1\\Rpc\\Revoke\\Controller' => 'Oauthreg\\V1\\Rpc\\Revoke\\RevokeControllerFactory',
            'Oauthreg\\V1\\Rpc\\SendCodeNewPhone\\Controller' => 'Oauthreg\\V1\\Rpc\\SendCodeNewPhone\\SendCodeNewPhoneControllerFactory',
            'Oauthreg\\V1\\Rpc\\VerifySmsNewPhone\\Controller' => 'Oauthreg\\V1\\Rpc\\VerifySmsNewPhone\\VerifySmsNewPhoneControllerFactory',
        ),
    ),
    'zf-rpc' => array(
        'Oauthreg\\V1\\Rpc\\VerifyEmail\\Controller' => array(
            'service_name' => 'VerifyEmail',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'oauthreg.rpc.verify-email',
        ),
        'Oauthreg\\V1\\Rpc\\VerifyRecoverPassword\\Controller' => array(
            'service_name' => 'VerifyRecoverPassword',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'oauthreg.rpc.verify-recover-password',
        ),
        'Oauthreg\\V1\\Rpc\\SetNewPassword\\Controller' => array(
            'service_name' => 'SetNewPassword',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'oauthreg.rpc.set-new-password',
        ),
        'Oauthreg\\V1\\Rpc\\VerifySms\\Controller' => array(
            'service_name' => 'VerifySms',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'oauthreg.rpc.verify-sms',
        ),
        'Oauthreg\\V1\\Rpc\\SendCode\\Controller' => array(
            'service_name' => 'SendCode',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'oauthreg.rpc.send-code',
        ),
        'Oauthreg\\V1\\Rpc\\Revoke\\Controller' => array(
            'service_name' => 'Revoke',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'oauthreg.rpc.revoke',
        ),
        'Oauthreg\\V1\\Rpc\\SendCodeNewPhone\\Controller' => array(
            'service_name' => 'SendCodeNewPhone',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'oauthreg.rpc.send-code-new-phone',
        ),
        'Oauthreg\\V1\\Rpc\\VerifySmsNewPhone\\Controller' => array(
            'service_name' => 'VerifySmsNewPhone',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'oauthreg.rpc.verify-sms-new-phone',
        ),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(
            'Oauthreg\\V1\\Rest\\Oauthuser\\Controller' => array(
                'collection' => array(
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
                'entity' => array(
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
            ),
        ),
    ),
);
