<?php
return array(
    'router' => array(
        'routes' => array(
            'openy.rest.station' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/station[/:station_id]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Station\\Controller',
                    ),
                ),
            ),
            'openy.rest.price' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/price[/:price_id]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Price\\Controller',
                    ),
                ),
            ),
            'openy.rest.fueltype' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/fueltype[/:fueltype_id]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Fueltype\\Controller',
                    ),
                ),
            ),
            'openy.rest.favoritestation' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/favoritestation[/:favoritestation_id]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Favoritestation\\Controller',
                    ),
                ),
            ),
            'openy.rest.creditcard' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/creditcard[/:creditcard_id]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Creditcard\\Controller',
                    ),
                ),
            ),
            'openy.rest.liststations' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/liststations[/:liststations_id][/:point]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Liststations\\Controller',
                    ),
                ),
            ),
            'openy.rest.preference' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/preference[/:preference_id]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Preference\\Controller',
                    ),
                ),
            ),
            'openy.rpc.send-feedback' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/sendfeedback',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rpc\\SendFeedback\\Controller',
                        'action' => 'sendFeedback',
                    ),
                ),
            ),
            'openy.rest.transaction' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/transaction[/:transaction_id]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Transaction\\Controller',
                    ),
                ),
            ),
            'openy.rest.orders' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/orders[/:orders_id]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Orders\\Controller',
                    ),
                ),
            ),
            'openy.rest.receipts' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/receipts[/:receipt_id]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Receipt\\Controller',
                    ),
                ),
            ),
            'openy.rest.invoices' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/invoice[/:invoice_id]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Invoice\\Controller',
                    ),
                ),
            ),
            'openy.rest.company' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/company[/:company_id]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Company\\Controller',
                    ),
                ),
            ),
            'openy.rest.refuel' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/refuel[/:refuel_id]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Refuel\\Controller',
                    ),
                ),
            ),
            'openy.rpc.verify-pin' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/verifypin[/:pin][/:iduser]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rpc\\VerifyPin\\Controller',
                        'action' => 'verifyPin',
                    ),
                ),
            ),
            'openy.rpc.creditcardvalidation' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/creditcardvalidation',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rpc\\Creditcardvalidation\\Controller',
                        'action' => 'creditcardvalidation',
                    ),
                ),
            ),
            'openy.rest.collect' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/collect[/:collect_id]',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rest\\Collect\\Controller',
                    ),
                ),
            ),
            'openy.rpc.get-invoice' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/getinvoice',
                    'defaults' => array(
                        'controller' => 'Openy\\V1\\Rpc\\GetInvoice\\Controller',
                        'action' => 'getInvoice',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'openy.rest.station',
            1 => 'openy.rest.price',
            2 => 'openy.rest.fueltype',
            3 => 'openy.rest.favoritestation',
            4 => 'openy.rest.creditcard',
            5 => 'openy.rest.liststations',
            6 => 'openy.rest.preference',
            7 => 'openy.rpc.send-feedback',
            8 => 'openy.rest.transaction',
            9 => 'openy.rest.orders',
            10 => 'openy.rest.company',
            11 => 'openy.rest.refuel',
            12 => 'openy.rpc.verify-pin',
            13 => 'openy.rpc.creditcardvalidation',
            14 => 'openy.rest.collect',
            15 => 'openy.rest.receipts',
            16 => 'openy.rest.invoices',
            17 => 'openy.rpc.get-invoice',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Openy\\V1\\Rest\\Station\\StationResource' => 'Openy\\V1\\Rest\\Station\\StationResourceFactory',
            'Openy\\V1\\Rest\\Price\\PriceResource' => 'Openy\\V1\\Rest\\Price\\PriceResourceFactory',
            'Openy\\V1\\Rest\\Fueltype\\FueltypeResource' => 'Openy\\V1\\Rest\\Fueltype\\FueltypeResourceFactory',
            'Openy\\V1\\Rest\\Favoritestation\\FavoritestationResource' => 'Openy\\V1\\Rest\\Favoritestation\\FavoritestationResourceFactory',
            'Openy\\V1\\Rest\\Creditcard\\CreditcardResource' => 'Openy\\V1\\Rest\\Creditcard\\CreditcardResourceFactory',
            'Openy\\V1\\Rest\\Liststations\\ListstationsResource' => 'Openy\\V1\\Rest\\Liststations\\ListstationsResourceFactory',
            'Openy\\V1\\Rest\\Preference\\PreferenceResource' => 'Openy\\V1\\Rest\\Preference\\PreferenceResourceFactory',
            'Openy\\V1\\Rest\\Transaction\\TransactionResource' => 'Openy\\V1\\Rest\\Transaction\\TransactionResourceFactory',
            'Openy\\V1\\Rest\\Orders\\OrdersResource' => 'Openy\\V1\\Rest\\Orders\\OrdersResourceFactory',
            'Openy\\V1\\Rest\\Receipt\\ReceiptResource' => 'Openy\\V1\\Rest\\Receipt\\ReceiptResourceFactory',
            'Openy\\V1\\Rest\\Invoice\\InvoiceResource' => 'Openy\\V1\\Rest\\Invoice\\InvoiceResourceFactory',
            'Openy\\V1\\Rest\\Company\\CompanyResource' => 'Openy\\V1\\Rest\\Company\\CompanyResourceFactory',
            'Openy\\V1\\Rest\\Refuel\\RefuelResource' => 'Openy\\V1\\Rest\\Refuel\\RefuelResourceFactory',
            'Openy\\V1\\Rest\\Collect\\CollectResource' => 'Openy\\V1\\Rest\\Collect\\CollectResourceFactory',
        ),
    ),
    'zf-rest' => array(
        'Openy\\V1\\Rest\\Station\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Station\\StationResource',
            'route_name' => 'openy.rest.station',
            'route_identifier_name' => 'station_id',
            'collection_name' => 'station',
            'entity_http_methods' => array(
                0 => 'GET',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
            ),
            'collection_query_whitelist' => array(
                0 => 'action',
                1 => 'psize',
                2 => 'date',
                3 => 'point',
            ),
            'page_size' => '100',
            'page_size_param' => 'perpage',
            'entity_class' => 'Openy\\V1\\Rest\\Station\\StationEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Station\\StationCollection',
            'service_name' => 'Station',
        ),
        'Openy\\V1\\Rest\\Price\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Price\\PriceResource',
            'route_name' => 'openy.rest.price',
            'route_identifier_name' => 'price_id',
            'collection_name' => 'price',
            'entity_http_methods' => array(),
            'collection_http_methods' => array(
                0 => 'GET',
            ),
            'collection_query_whitelist' => array(
                0 => 'psize',
                1 => 'action',
                2 => 'date',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Openy\\V1\\Rest\\Price\\PriceEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Price\\PriceCollection',
            'service_name' => 'Price',
        ),
        'Openy\\V1\\Rest\\Fueltype\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Fueltype\\FueltypeResource',
            'route_name' => 'openy.rest.fueltype',
            'route_identifier_name' => 'fueltype_id',
            'collection_name' => 'fueltype',
            'entity_http_methods' => array(),
            'collection_http_methods' => array(
                0 => 'GET',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Openy\\V1\\Rest\\Fueltype\\FueltypeEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Fueltype\\FueltypeCollection',
            'service_name' => 'Fueltype',
        ),
        'Openy\\V1\\Rest\\Favoritestation\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Favoritestation\\FavoritestationResource',
            'route_name' => 'openy.rest.favoritestation',
            'route_identifier_name' => 'favoritestation_id',
            'collection_name' => 'favoritestation',
            'entity_http_methods' => array(),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
                2 => 'DELETE',
            ),
            'collection_query_whitelist' => array(
                0 => 'idoffstation',
                1 => 'iduser',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Openy\\V1\\Rest\\Favoritestation\\FavoritestationEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Favoritestation\\FavoritestationCollection',
            'service_name' => 'Favoritestation',
        ),
        'Openy\\V1\\Rest\\Creditcard\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Creditcard\\CreditcardResource',
            'route_name' => 'openy.rest.creditcard',
            'route_identifier_name' => 'creditcard_id',
            'collection_name' => 'creditcard',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'DELETE',
                2 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Openy\\V1\\Rest\\Creditcard\\CreditcardEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Creditcard\\CreditcardCollection',
            'service_name' => 'Creditcard',
        ),
        'Openy\\V1\\Rest\\Liststations\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Liststations\\ListstationsResource',
            'route_name' => 'openy.rest.liststations',
            'route_identifier_name' => 'liststations_id',
            'collection_name' => 'liststations',
            'entity_http_methods' => array(
                0 => 'GET',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
            ),
            'collection_query_whitelist' => array(
                0 => 'order',
                1 => 'point',
                2 => 'ftype',
                3 => 'filter',
            ),
            'page_size' => '50',
            'page_size_param' => null,
            'entity_class' => 'Openy\\V1\\Rest\\Liststations\\ListstationsEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Liststations\\ListstationsCollection',
            'service_name' => 'Liststations',
        ),
        'Openy\\V1\\Rest\\Preference\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Preference\\PreferenceResource',
            'route_name' => 'openy.rest.preference',
            'route_identifier_name' => 'preference_id',
            'collection_name' => 'preference',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
            ),
            'collection_http_methods' => array(),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Openy\\V1\\Rest\\Preference\\PreferenceEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Preference\\PreferenceCollection',
            'service_name' => 'Preference',
        ),
        'Openy\\V1\\Rest\\Transaction\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Transaction\\TransactionResource',
            'route_name' => 'openy.rest.transaction',
            'route_identifier_name' => 'transaction_id',
            'collection_name' => 'transaction',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'DELETE',
                3 => 'PUT',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(
                0 => 'idcreditcard',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Openy\\Model\\Transaction\\TransactionEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Transaction\\TransactionCollection',
            'service_name' => 'Transaction',
        ),
        'Openy\\V1\\Rest\\Receipt\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Receipt\\ReceiptResource',
            'route_name' => 'openy.rest.receipts',
            'route_identifier_name' => 'receipt_id',
            'collection_name' => 'receipts',
            'entity_http_methods' => array(
                0 => 'GET',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
            ),
            'collection_query_whitelist' => array(
                0 => 'until',
                1 => 'idorder',
            ),
            'page_size' => '10',
            'page_size_param' => null,
            'entity_class' => 'Openy\\V1\\Rest\\Receipt\\ReceiptEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Receipt\\ReceiptCollection',
            'service_name' => 'Receipt',
        ),
        'Openy\\V1\\Rest\\Invoice\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Invoice\\InvoiceResource',
            'route_name' => 'openy.rest.invoices',
            'route_identifier_name' => 'invoice_id',
            'collection_name' => 'invoices',
            'entity_http_methods' => array(
                0 => 'GET',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(
                0 => 'until',
                1 => 'receiptid',
            ),
            'page_size' => '10',
            'page_size_param' => null,
            'entity_class' => 'Openy\\V1\\Rest\\Invoice\\InvoiceEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Invoice\\InvoiceCollection',
            'service_name' => 'Invoice',
        ),
        'Openy\\V1\\Rest\\Orders\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Orders\\OrdersResource',
            'route_name' => 'openy.rest.orders',
            'route_identifier_name' => 'orders_id',
            'collection_name' => 'orders',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Openy\\Model\\Order\\OrderEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Orders\\OrdersCollection',
            'service_name' => 'Orders',
        ),
        'Openy\\V1\\Rest\\Company\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Company\\CompanyResource',
            'route_name' => 'openy.rest.company',
            'route_identifier_name' => 'company_id',
            'collection_name' => 'company',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Openy\\V1\\Rest\\Company\\CompanyEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Company\\CompanyCollection',
            'service_name' => 'Company',
        ),
        'Openy\\V1\\Rest\\Refuel\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Refuel\\RefuelResource',
            'route_name' => 'openy.rest.refuel',
            'route_identifier_name' => 'refuel_id',
            'collection_name' => 'refuel',
            'entity_http_methods' => array(),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Openy\\V1\\Rest\\Refuel\\RefuelEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Refuel\\RefuelCollection',
            'service_name' => 'Refuel',
        ),
        'Openy\\V1\\Rest\\Collect\\Controller' => array(
            'listener' => 'Openy\\V1\\Rest\\Collect\\CollectResource',
            'route_name' => 'openy.rest.collect',
            'route_identifier_name' => 'collect_id',
            'collection_name' => 'collect',
            'entity_http_methods' => array(
                0 => 'GET',
            ),
            'collection_http_methods' => array(
                0 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Openy\\V1\\Rest\\Collect\\CollectEntity',
            'collection_class' => 'Openy\\V1\\Rest\\Collect\\CollectCollection',
            'service_name' => 'Collect',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Openy\\V1\\Rest\\Station\\Controller' => 'Json',
            'Openy\\V1\\Rest\\Price\\Controller' => 'Json',
            'Openy\\V1\\Rest\\Fueltype\\Controller' => 'Json',
            'Openy\\V1\\Rest\\Favoritestation\\Controller' => 'Json',
            'Openy\\V1\\Rest\\Creditcard\\Controller' => 'HalJson',
            'Openy\\V1\\Rest\\Liststations\\Controller' => 'HalJson',
            'Openy\\V1\\Rest\\Preference\\Controller' => 'HalJson',
            'Openy\\V1\\Rpc\\SendFeedback\\Controller' => 'Json',
            'Openy\\V1\\Rest\\Transaction\\Controller' => 'HalJson',
            'Openy\\V1\\Rest\\Orders\\Controller' => 'HalJson',
            'Openy\\V1\\Rest\\Receipt\\Controller' => 'HalJson',
            'Openy\\V1\\Rest\\Invoice\\Controller' => 'HalJson',
            'Openy\\V1\\Rest\\Company\\Controller' => 'HalJson',
            'Openy\\V1\\Rest\\Refuel\\Controller' => 'HalJson',
            'Openy\\V1\\Rpc\\VerifyPin\\Controller' => 'Json',
            'Openy\\V1\\Rpc\\Creditcardvalidation\\Controller' => 'Json',
            'Openy\\V1\\Rest\\Collect\\Controller' => 'HalJson',
            'Openy\\V1\\Rpc\\GetInvoice\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'Openy\\V1\\Rest\\Station\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
                2 => 'application/hal+json',
            ),
            'Openy\\V1\\Rest\\Price\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Fueltype\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Favoritestation\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Creditcard\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Liststations\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Preference\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Openy\\V1\\Rpc\\SendFeedback\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Openy\\V1\\Rest\\Transaction\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Orders\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Receipt\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Invoice\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Company\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Refuel\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Openy\\V1\\Rpc\\VerifyPin\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Openy\\V1\\Rpc\\Creditcardvalidation\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Openy\\V1\\Rest\\Collect\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Openy\\V1\\Rpc\\GetInvoice\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'Openy\\V1\\Rest\\Station\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Price\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Fueltype\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Favoritestation\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Creditcard\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Liststations\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Preference\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rpc\\SendFeedback\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Transaction\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Orders\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Receipt\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Invoice\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Company\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Refuel\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rpc\\VerifyPin\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rpc\\Creditcardvalidation\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rest\\Collect\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
            'Openy\\V1\\Rpc\\GetInvoice\\Controller' => array(
                0 => 'application/vnd.openy.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Openy\\V1\\Rest\\Station\\StationEntity' => array(
                'entity_identifier_name' => 'idoffstation',
                'route_name' => 'openy.rest.station',
                'route_identifier_name' => 'station_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Station\\StationCollection' => array(
                'entity_identifier_name' => 'idoffstation',
                'route_name' => 'openy.rest.station',
                'route_identifier_name' => 'station_id',
                'is_collection' => true,
            ),
            'Openy\\V1\\Rest\\Price\\PriceEntity' => array(
                'entity_identifier_name' => 'idprice',
                'route_name' => 'openy.rest.price',
                'route_identifier_name' => 'price_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Price\\PriceCollection' => array(
                'entity_identifier_name' => 'idprice',
                'route_name' => 'openy.rest.price',
                'route_identifier_name' => 'price_id',
                'is_collection' => true,
            ),
            'Openy\\V1\\Rest\\Fueltype\\FueltypeEntity' => array(
                'entity_identifier_name' => 'idfueltype',
                'route_name' => 'openy.rest.fueltype',
                'route_identifier_name' => 'fueltype_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Fueltype\\FueltypeCollection' => array(
                'entity_identifier_name' => 'idfueltype',
                'route_name' => 'openy.rest.fueltype',
                'route_identifier_name' => 'fueltype_id',
                'is_collection' => true,
            ),
            'Openy\\V1\\Rest\\Favoritestation\\FavoritestationEntity' => array(
                'entity_identifier_name' => 'idfavorite',
                'route_name' => 'openy.rest.favoritestation',
                'route_identifier_name' => 'favoritestation_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Favoritestation\\FavoritestationCollection' => array(
                'entity_identifier_name' => 'idfavorite',
                'route_name' => 'openy.rest.favoritestation',
                'route_identifier_name' => 'favoritestation_id',
                'is_collection' => true,
            ),
            'Openy\\V1\\Rest\\Creditcard\\CreditcardEntity' => array(
                'entity_identifier_name' => 'idcreditcard',
                'route_name' => 'openy.rest.creditcard',
                'route_identifier_name' => 'creditcard_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Creditcard\\CreditcardCollection' => array(
                'entity_identifier_name' => 'idcreditcard',
                'route_name' => 'openy.rest.creditcard',
                'route_identifier_name' => 'creditcard_id',
                'is_collection' => true,
            ),
            'Openy\\V1\\Rest\\Liststations\\ListstationsEntity' => array(
                'entity_identifier_name' => 'idoffstation',
                'route_name' => 'openy.rest.liststations',
                'route_identifier_name' => 'liststations_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Liststations\\ListstationsCollection' => array(
                'entity_identifier_name' => 'idoffstation',
                'route_name' => 'openy.rest.liststations',
                'route_identifier_name' => 'liststations_id',
                'is_collection' => true,
            ),
            'Openy\\V1\\Rest\\Preference\\PreferenceEntity' => array(
                'entity_identifier_name' => 'iduser',
                'route_name' => 'openy.rest.preference',
                'route_identifier_name' => 'preference_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Preference\\PreferenceCollection' => array(
                'entity_identifier_name' => 'iduser',
                'route_name' => 'openy.rest.preference',
                'route_identifier_name' => 'preference_id',
                'is_collection' => true,
            ),
            'Openy\\V1\\Rest\\Transaction\\TransactionEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'openy.rest.transaction',
                'route_identifier_name' => 'transaction_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Transaction\\TransactionCollection' => array(
                'entity_identifier_name' => 'transactionid',
                'route_name' => 'openy.rest.transaction',
                'route_identifier_name' => 'transaction_id',
                'is_collection' => true,
            ),
            'Openy\\Model\\Transaction\\TransactionEntity' => array(
                'entity_identifier_name' => 'transactionid',
                'route_name' => 'openy.rest.transaction',
                'route_identifier_name' => 'transaction_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Orders\\OrdersCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'openy.rest.orders',
                'route_identifier_name' => 'orders_id',
                'is_collection' => true,
            ),
            'Openy\\Model\\Order\\OrderEntity' => array(
                'entity_identifier_name' => 'idorder',
                'route_name' => 'openy.rest.orders',
                'route_identifier_name' => 'orders_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Company\\CompanyEntity' => array(
                'entity_identifier_name' => 'idcompany',
                'route_name' => 'openy.rest.company',
                'route_identifier_name' => 'company_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Company\\CompanyCollection' => array(
                'entity_identifier_name' => 'idcompany',
                'route_name' => 'openy.rest.company',
                'route_identifier_name' => 'company_id',
                'is_collection' => true,
            ),
            'Openy\\V1\\Rest\\Refuel\\RefuelEntity' => array(
                'entity_identifier_name' => 'idorder',
                'route_name' => 'openy.rest.refuel',
                'route_identifier_name' => 'refuel_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Refuel\\RefuelCollection' => array(
                'entity_identifier_name' => 'idorder',
                'route_name' => 'openy.rest.refuel',
                'route_identifier_name' => 'refuel_id',
                'is_collection' => true,
            ),
            'Openy\\V1\\Rest\\Collect\\CollectEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'openy.rest.collect',
                'route_identifier_name' => 'collect_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Collect\\CollectCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'openy.rest.collect',
                'route_identifier_name' => 'collect_id',
                'is_collection' => true,
            ),
            'Openy\\V1\\Rest\\Receipt\\ReceiptEntity' => array(
                'entity_identifier_name' => 'receiptid',
                'route_name' => 'openy.rest.receipts',
                'route_identifier_name' => 'receipt_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Receipt\\ReceiptCollection' => array(
                'entity_identifier_name' => 'receiptid',
                'route_name' => 'openy.rest.receipts',
                'route_identifier_name' => 'receipt_id',
                'is_collection' => true,
            ),
            'Openy\\V1\\Rest\\Invoice\\InvoiceEntity' => array(
                'entity_identifier_name' => 'idinvoice',
                'route_name' => 'openy.rest.invoices',
                'route_identifier_name' => 'invoice_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Openy\\V1\\Rest\\Invoice\\InvoiceCollection' => array(
                'entity_identifier_name' => 'idinvoice',
                'route_name' => 'openy.rest.invoices',
                'route_identifier_name' => 'invoice_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-content-validation' => array(
        'Openy\\V1\\Rest\\Station\\Controller' => array(
            'input_filter' => 'Openy\\V1\\Rest\\Station\\Validator',
        ),
        'Openy\\V1\\Rest\\Price\\Controller' => array(
            'input_filter' => 'Openy\\V1\\Rest\\Price\\Validator',
        ),
        'Openy\\V1\\Rest\\Fueltype\\Controller' => array(
            'input_filter' => 'Openy\\V1\\Rest\\Fueltype\\Validator',
        ),
        'Openy\\V1\\Rest\\Favoritestation\\Controller' => array(
            'input_filter' => 'Openy\\V1\\Rest\\Favoritestation\\Validator',
        ),
        'Openy\\V1\\Rest\\Creditcard\\Controller' => array(
            'input_filter' => 'Openy\\V1\\Rest\\Creditcard\\Validator',
        ),
        'Openy\\V1\\Rest\\Liststations\\Controller' => array(
            'input_filter' => 'Openy\\V1\\Rest\\Liststations\\Validator',
        ),
        'Openy\\V1\\Rest\\Preference\\Controller' => array(
            'input_filter' => 'Openy\\V1\\Rest\\Preference\\Validator',
        ),
        'Openy\\V1\\Rpc\\SendFeedback\\Controller' => array(
            'input_filter' => 'Openy\\V1\\Rpc\\SendFeedback\\Validator',
        ),
        'Openy\\V1\\Rest\\Transaction\\Controller' => array(
            'input_filter' => 'Openy\\V1\\Rest\\Transaction\\Validator',
        ),
        'Openy\\V1\\Rest\\Refuel\\Controller' => array(
            'input_filter' => 'Openy\\V1\\Rest\\Refuel\\Validator',
        ),
        'Openy\\V1\\Rpc\\Creditcardvalidation\\Controller' => array(
            'input_filter' => 'Openy\\V1\\Rpc\\Creditcardvalidation\\Validator',
        ),
        'Openy\\V1\\Rest\\Collect\\Controller' => array(
            'input_filter' => 'Openy\\V1\\Rest\\Collect\\Validator',
        ),
        'Openy\\V1\\Rpc\\GetInvoice\\Controller' => array(
            'input_filter' => 'Openy\\V1\\Rpc\\GetInvoice\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'Openy\\V1\\Rest\\Station\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'idoffstation',
                'description' => 'Station Id',
                'allow_empty' => false,
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'name',
                'description' => 'Station name',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'lat',
                'description' => 'Latitue',
            ),
            3 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'lng',
                'description' => 'Longitude',
            ),
            4 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'address',
                'description' => 'Station address',
            ),
        ),
        'Openy\\V1\\Rest\\Price\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'idoffstation',
                'description' => 'Station id',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'idfueltype',
                'description' => 'Fueltype id',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'price',
                'description' => 'Fuel price',
            ),
        ),
        'Openy\\V1\\Rest\\Fueltype\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'idfueltype',
                'description' => 'Fueltype id',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'fueltype',
                'description' => 'Fueltype',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'fuelcode',
                'description' => 'Fuel code',
            ),
        ),
        'Openy\\V1\\Rest\\Appregister\\Validator' => array(
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
        ),
        'Openy\\V1\\Rest\\Register\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'email',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'password',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'first_name',
            ),
            3 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'last_name',
            ),
            4 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'phone_number',
            ),
        ),
        'Openy\\V1\\Rest\\Oauthuser\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'username',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'password',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'first_name',
            ),
            3 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'last_name',
            ),
        ),
        'Openy\\V1\\Rpc\\VerifyEmail\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'email',
            ),
        ),
        'Openy\\V1\\Rpc\\VerifyRecoverPassword\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'email',
            ),
        ),
        'Openy\\V1\\Rpc\\SetNewPassword\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'token',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'email',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'password',
            ),
        ),
        'Openy\\V1\\Rest\\Favoritestation\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'idoffstation',
            ),
        ),
        'Openy\\V1\\Rest\\Creditcard\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(
                            'casting' => true,
                        ),
                    ),
                ),
                'name' => 'favorite',
                'description' => 'Tells whenever a card must be marked as user favorite payment method',
                'continue_if_empty' => false,
                'allow_empty' => true,
            ),
            1 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(
                            'casting' => true,
                        ),
                    ),
                ),
                'name' => 'active',
                'description' => 'Activates or deactivates a credit card',
                'allow_empty' => true,
            ),
            2 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\Alpha',
                        'options' => array(
                            'allowwhitespace' => true,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'cardusername',
            ),
            3 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(
                            'message' => 'Year field must be an integer',
                        ),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\Between',
                        'options' => array(
                            'min' => '0',
                            'max' => '99',
                            'message' => 'Year field must be an integer between 0 and 99',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'year',
                'description' => 'Card expiration year',
            ),
            4 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Between',
                        'options' => array(
                            'min' => '1',
                            'max' => '12',
                            'message' => 'Month field must be an integer between 1 and 12',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'month',
                'description' => 'Card expiration month',
            ),
            5 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\CreditCard',
                        'options' => array(
                            'message' => 'Not a valid credit card',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'pan',
            ),
            6 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => '3',
                            'max' => '3',
                        ),
                    ),
                    1 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'cvv',
            ),
        ),
        'Openy\\V1\\Rest\\Liststations\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'idstation',
            ),
        ),
        'Openy\\V1\\Rest\\Preference\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'payment_pin',
            ),
            1 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'default_credit_card',
            ),
            2 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'inv_name',
            ),
            3 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'inv_country',
            ),
            4 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'inv_address',
            ),
            5 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'inv_locality',
            ),
            6 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'inv_postal_code',
            ),
            7 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'inv_document_type',
            ),
            8 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'inv_document',
            ),
            9 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'inv_cicle',
            ),
            10 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'locale',
            ),
        ),
        'Openy\\V1\\Rpc\\SendFeedback\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'to',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'from',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'subject',
            ),
            3 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'body',
            ),
        ),
        'Openy\\V1\\Rest\\Transaction\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Whitelist',
                        'options' => array(
                            'list' => array(
                                0 => '001',
                                1 => '002',
                            ),
                        ),
                    ),
                ),
                'name' => 'lastresponse',
                'error_message' => 'Last Response has to be a value from following:
001, 002, 003, SIS001, SIS002...',
            ),
            1 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'authorizationcode',
            ),
            2 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'merchantcode',
            ),
            3 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'amount',
            ),
            4 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'idcreditcard',
            ),
            5 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'transactiontype',
            ),
            6 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'terminal',
            ),
            7 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'transactionid',
            ),
        ),
        'Openy\\V1\\Rest\\Refuel\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'pump',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'fueltype',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'amount',
            ),
            3 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'email',
            ),
            4 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'userPin',
            ),
            5 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'antifraudPin',
            ),
            6 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'price',
            ),
            7 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'idoffstation',
            ),
        ),
        'Openy\\V1\\Rpc\\Creditcardvalidation\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '36',
                            'min' => '36',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'idcreditcard',
                'description' => 'The credit card id',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'amount',
            ),
        ),
        'Openy\\V1\\Rest\\Collect\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'pump',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'fueltype',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'idorder',
            ),
            3 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'idoffstation',
            ),
        ),
        'Openy\\V1\\Rpc\\GetInvoice\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'receipt',
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(
            'Openy\\V1\\Rest\\Station\\Controller' => array(
                'collection' => array(
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
                'entity' => array(
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
            ),
            'Openy\\V1\\Rest\\Fueltype\\Controller' => array(
                'collection' => array(
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
                'entity' => array(
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
            ),
            'Openy\\V1\\Rest\\Favoritestation\\Controller' => array(
                'collection' => array(
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
                'entity' => array(
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
            ),
            'Openy\\V1\\Rest\\Preference\\Controller' => array(
                'collection' => array(
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
                'entity' => array(
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => true,
                    'DELETE' => false,
                ),
            ),
            'Openy\\V1\\Rpc\\VerifyPin\\Controller' => array(
                'actions' => array(
                    'VerifyPin' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Openy\\V1\\Rest\\Creditcard\\Controller' => array(
                'collection' => array(
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
                'entity' => array(
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => true,
                    'DELETE' => true,
                ),
            ),
            'Openy\\V1\\Rpc\\Creditcardvalidation\\Controller' => array(
                'actions' => array(
                    'Creditcardvalidation' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Openy\\V1\\Rest\\Transaction\\Controller' => array(
                'collection' => array(
                    'GET' => true,
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
            'Openy\\V1\\Rest\\Receipt\\Controller' => array(
                'collection' => array(
                    'GET' => true,
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
            'Openy\\V1\\Rest\\Invoice\\Controller' => array(
                'collection' => array(
                    'GET' => true,
                    'POST' => true,
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
    'controllers' => array(
        'factories' => array(
            'Openy\\V1\\Rpc\\SendFeedback\\Controller' => 'Openy\\V1\\Rpc\\SendFeedback\\SendFeedbackControllerFactory',
            'Openy\\V1\\Rpc\\VerifyPin\\Controller' => 'Openy\\V1\\Rpc\\VerifyPin\\VerifyPinControllerFactory',
            'Openy\\V1\\Rpc\\Creditcardvalidation\\Controller' => 'Openy\\V1\\Rpc\\Creditcardvalidation\\CreditcardvalidationControllerFactory',
            'Openy\\V1\\Rpc\\GetInvoice\\Controller' => 'Openy\\V1\\Rpc\\GetInvoice\\GetInvoiceControllerFactory',
        ),
    ),
    'zf-rpc' => array(
        'Openy\\V1\\Rpc\\SendFeedback\\Controller' => array(
            'service_name' => 'SendFeedback',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'openy.rpc.send-feedback',
        ),
        'Openy\\V1\\Rpc\\VerifyPin\\Controller' => array(
            'service_name' => 'VerifyPin',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'openy.rpc.verify-pin',
        ),
        'Openy\\V1\\Rpc\\Creditcardvalidation\\Controller' => array(
            'service_name' => 'Creditcardvalidation',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'openy.rpc.creditcardvalidation',
        ),
        'Openy\\V1\\Rpc\\GetInvoice\\Controller' => array(
            'service_name' => 'GetInvoice',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'openy.rpc.get-invoice',
        ),
    ),
);
