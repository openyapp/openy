<?php
return array(
    'db' => array(
        'adapters' => array(
            'Db\\StatusLib' => array(),
        ),
    ),
    'zf-content-negotiation' => array(
        'selectors' => array(),
    ),
    'router' => array(
        'routes' => array(
            'oauth' => array(
                'options' => array(
                    'spec' => '%oauth%',
                    'regex' => '(?P<oauth>(/oauth))',
                ),
                'type' => 'regex',
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authentication' => array(
            'map' => array(
                'Openy\\V1' => 'oauth2_pdo',
                'Oauthreg\\V1' => 'oauth2_pdo',
                'Opypos\\V1' => 'oauth2_pdo',
            ),
        ),
    ),
);
