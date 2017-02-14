<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Opypos\\V1\\Rest\\Configuration\\ConfigurationResource' => 'Opypos\\V1\\Rest\\Configuration\\ConfigurationResourceFactory',
            'Opypos\\V1\\Rest\\Price\\PriceResource' => 'Opypos\\V1\\Rest\\Price\\PriceResourceFactory',
            'Opypos\\V1\\Rest\\Pump\\PumpResource' => 'Opypos\\V1\\Rest\\Pump\\PumpResourceFactory',
            'Opypos\\V1\\Rest\\Pumpstatus\\PumpstatusResource' => 'Opypos\\V1\\Rest\\Pumpstatus\\PumpstatusResourceFactory',
            'Opypos\\V1\\Rest\\Client\\ClientResource' => 'Opypos\\V1\\Rest\\Client\\ClientResourceFactory',
            'Opypos\\V1\\Rest\\Closest\\ClosestResource' => 'Opypos\\V1\\Rest\\Closest\\ClosestResourceFactory',
            'Opypos\\V1\\Rest\\Station\\StationResource' => 'Opypos\\V1\\Rest\\Station\\StationResourceFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'opypos.rest.configuration' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/opystation/configuration[/:idoffstation]',
                    'defaults' => array(
                        'controller' => 'Opypos\\V1\\Rest\\Configuration\\Controller',
                    ),
                ),
            ),
            'opypos.rest.price' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/opystation/price[/:idoffstation][/:openy]',
                    'defaults' => array(
                        'controller' => 'Opypos\\V1\\Rest\\Price\\Controller',
                    ),
                ),
            ),
            'opypos.rpc.ping' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/opystation/ping/[:idoffstation]',
                    'defaults' => array(
                        'controller' => 'Opypos\\V1\\Rpc\\Ping\\Controller',
                        'action' => 'ping',
                    ),
                ),
            ),
            'opypos.rest.pump' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/opystation/pump[/:idoffstation]',
                    'defaults' => array(
                        'controller' => 'Opypos\\V1\\Rest\\Pump\\Controller',
                    ),
                ),
            ),
            'opypos.rest.pumpstatus' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/opystation/pumpstatus[/:idoffstation]',
                    'defaults' => array(
                        'controller' => 'Opypos\\V1\\Rest\\Pumpstatus\\Controller',
                    ),
                ),
            ),
            'opypos.rest.client' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/client[/:client_id]',
                    'defaults' => array(
                        'controller' => 'Opypos\\V1\\Rest\\Client\\Controller',
                    ),
                ),
            ),
            'opypos.rpc.monitor-raise-pump' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/opystation/monitorraisepump/:idopystation/:pump/:fueltype/:idorder',
                    'defaults' => array(
                        'controller' => 'Opypos\\V1\\Rpc\\MonitorRaisePump\\Controller',
                        'action' => 'monitorRaisePump',
                    ),
                ),
            ),
            'opypos.rpc.monitor-fuel-pumped' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/opystation/monitorfuelpumped/:idopystation/:pump',
                    'defaults' => array(
                        'controller' => 'Opypos\\V1\\Rpc\\MonitorFuelPumped\\Controller',
                        'action' => 'monitorFuelPumped',
                    ),
                ),
            ),
            'opypos.rpc.monitor-hang-pump' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/opystation/monitorhangpump/:idopystation/:pump/:fueltype/:idorder',
                    'defaults' => array(
                        'controller' => 'Opypos\\V1\\Rpc\\MonitorHangPump\\Controller',
                        'action' => 'monitorHangPump',
                    ),
                ),
            ),
            'opypos.rest.closest' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/opystation/closest[/:idoffstation]',
                    'defaults' => array(
                        'controller' => 'Opypos\\V1\\Rest\\Closest\\Controller',
                    ),
                ),
            ),
            'opypos.rest.station' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/opystation/station[/:idstation]',
                    'defaults' => array(
                        'controller' => 'Opypos\\V1\\Rest\\Station\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'opypos.rest.configuration',
            1 => 'opypos.rest.price',
            2 => 'opypos.rpc.ping',
            3 => 'opypos.rest.pump',
            4 => 'opypos.rest.pumpstatus',
            5 => 'opypos.rest.client',
            6 => 'opypos.rpc.monitor-raise-pump',
            7 => 'opypos.rpc.monitor-fuel-pumped',
            8 => 'opypos.rpc.monitor-hang-pump',
            9 => 'opypos.rest.closest',
            10 => 'opypos.rest.station',
        ),
    ),
    'zf-rest' => array(
        'Opypos\\V1\\Rest\\Configuration\\Controller' => array(
            'listener' => 'Opypos\\V1\\Rest\\Configuration\\ConfigurationResource',
            'route_name' => 'opypos.rest.configuration',
            'route_identifier_name' => 'idoffstation',
            'collection_name' => 'configuration',
            'entity_http_methods' => array(
                0 => 'GET',
            ),
            'collection_http_methods' => array(),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Opypos\\V1\\Rest\\Configuration\\ConfigurationEntity',
            'collection_class' => 'Opypos\\V1\\Rest\\Configuration\\ConfigurationCollection',
            'service_name' => 'Configuration',
        ),
        'Opypos\\V1\\Rest\\Price\\Controller' => array(
            'listener' => 'Opypos\\V1\\Rest\\Price\\PriceResource',
            'route_name' => 'opypos.rest.price',
            'route_identifier_name' => 'idoffstation',
            'collection_name' => 'price',
            'entity_http_methods' => array(
                0 => 'GET',
            ),
            'collection_http_methods' => array(),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Opypos\\V1\\Rest\\Price\\PriceEntity',
            'collection_class' => 'Opypos\\V1\\Rest\\Price\\PriceCollection',
            'service_name' => 'Price',
        ),
        'Opypos\\V1\\Rest\\Pump\\Controller' => array(
            'listener' => 'Opypos\\V1\\Rest\\Pump\\PumpResource',
            'route_name' => 'opypos.rest.pump',
            'route_identifier_name' => 'idoffstation',
            'collection_name' => 'pump',
            'entity_http_methods' => array(
                0 => 'GET',
            ),
            'collection_http_methods' => array(),
            'collection_query_whitelist' => array(
                0 => 'point',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Opypos\\V1\\Rest\\Pump\\PumpEntity',
            'collection_class' => 'Opypos\\V1\\Rest\\Pump\\PumpCollection',
            'service_name' => 'Pump',
        ),
        'Opypos\\V1\\Rest\\Pumpstatus\\Controller' => array(
            'listener' => 'Opypos\\V1\\Rest\\Pumpstatus\\PumpstatusResource',
            'route_name' => 'opypos.rest.pumpstatus',
            'route_identifier_name' => 'idoffstation',
            'collection_name' => 'pumpstatus',
            'entity_http_methods' => array(
                0 => 'GET',
            ),
            'collection_http_methods' => array(),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Opypos\\V1\\Rest\\Pumpstatus\\PumpstatusEntity',
            'collection_class' => 'Opypos\\V1\\Rest\\Pumpstatus\\PumpstatusCollection',
            'service_name' => 'Pumpstatus',
        ),
        'Opypos\\V1\\Rest\\Client\\Controller' => array(
            'listener' => 'Opypos\\V1\\Rest\\Client\\ClientResource',
            'route_name' => 'opypos.rest.client',
            'route_identifier_name' => 'client_id',
            'collection_name' => 'client',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Opypos\\V1\\Rest\\Client\\ClientEntity',
            'collection_class' => 'Opypos\\V1\\Rest\\Client\\ClientCollection',
            'service_name' => 'Client',
        ),
        'Opypos\\V1\\Rest\\Closest\\Controller' => array(
            'listener' => 'Opypos\\V1\\Rest\\Closest\\ClosestResource',
            'route_name' => 'opypos.rest.closest',
            'route_identifier_name' => 'idoffstation',
            'collection_name' => 'closest',
            'entity_http_methods' => array(
                0 => 'GET',
            ),
            'collection_http_methods' => array(),
            'collection_query_whitelist' => array(
                0 => 'point',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Opypos\\V1\\Rest\\Closest\\ClosestEntity',
            'collection_class' => 'Opypos\\V1\\Rest\\Closest\\ClosestCollection',
            'service_name' => 'Closest',
        ),
        'Opypos\\V1\\Rest\\Station\\Controller' => array(
            'listener' => 'Opypos\\V1\\Rest\\Station\\StationResource',
            'route_name' => 'opypos.rest.station',
            'route_identifier_name' => 'idstation',
            'collection_name' => 'station',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Opypos\\V1\\Rest\\Station\\StationEntity',
            'collection_class' => 'Opypos\\V1\\Rest\\Station\\StationCollection',
            'service_name' => 'Station',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Opypos\\V1\\Rest\\Configuration\\Controller' => 'HalJson',
            'Opypos\\V1\\Rest\\Price\\Controller' => 'HalJson',
            'Opypos\\V1\\Rpc\\Ping\\Controller' => 'Json',
            'Opypos\\V1\\Rest\\Pump\\Controller' => 'HalJson',
            'Opypos\\V1\\Rest\\Pumpstatus\\Controller' => 'HalJson',
            'Opypos\\V1\\Rest\\Client\\Controller' => 'HalJson',
            'Opypos\\V1\\Rpc\\MonitorRaisePump\\Controller' => 'Json',
            'Opypos\\V1\\Rpc\\MonitorFuelPumped\\Controller' => 'Json',
            'Opypos\\V1\\Rpc\\MonitorHangPump\\Controller' => 'Json',
            'Opypos\\V1\\Rest\\Closest\\Controller' => 'HalJson',
            'Opypos\\V1\\Rest\\Station\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'Opypos\\V1\\Rest\\Configuration\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Opypos\\V1\\Rest\\Price\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Opypos\\V1\\Rpc\\Ping\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Opypos\\V1\\Rest\\Pump\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Opypos\\V1\\Rest\\Pumpstatus\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Opypos\\V1\\Rest\\Client\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Opypos\\V1\\Rpc\\MonitorRaisePump\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Opypos\\V1\\Rpc\\MonitorFuelPumped\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Opypos\\V1\\Rpc\\MonitorHangPump\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Opypos\\V1\\Rest\\Closest\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Opypos\\V1\\Rest\\Station\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'Opypos\\V1\\Rest\\Configuration\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
            ),
            'Opypos\\V1\\Rest\\Price\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
            ),
            'Opypos\\V1\\Rpc\\Ping\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
            ),
            'Opypos\\V1\\Rest\\Pump\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
            ),
            'Opypos\\V1\\Rest\\Pumpstatus\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
            ),
            'Opypos\\V1\\Rest\\Client\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
            ),
            'Opypos\\V1\\Rpc\\MonitorRaisePump\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
            ),
            'Opypos\\V1\\Rpc\\MonitorFuelPumped\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
            ),
            'Opypos\\V1\\Rpc\\MonitorHangPump\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
            ),
            'Opypos\\V1\\Rest\\Closest\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
            ),
            'Opypos\\V1\\Rest\\Station\\Controller' => array(
                0 => 'application/vnd.opypos.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Opypos\\V1\\Rest\\Configuration\\ConfigurationEntity' => array(
                'entity_identifier_name' => 'idopystation',
                'route_name' => 'opypos.rest.configuration',
                'route_identifier_name' => 'idoffstation',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Opypos\\V1\\Rest\\Configuration\\ConfigurationCollection' => array(
                'entity_identifier_name' => 'idopystation',
                'route_name' => 'opypos.rest.configuration',
                'route_identifier_name' => 'idoffstation',
                'is_collection' => true,
            ),
            'Opypos\\V1\\Rest\\Price\\PriceEntity' => array(
                'entity_identifier_name' => 'opyProductType',
                'route_name' => 'opypos.rest.price',
                'route_identifier_name' => 'idoffstation',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Opypos\\V1\\Rest\\Price\\PriceCollection' => array(
                'entity_identifier_name' => 'opyProductType',
                'route_name' => 'opypos.rest.price',
                'route_identifier_name' => 'idoffstation',
                'is_collection' => true,
            ),
            'Opypos\\V1\\Rest\\Pump\\PumpEntity' => array(
                'entity_identifier_name' => 'idopystation',
                'route_name' => 'opypos.rest.pump',
                'route_identifier_name' => 'idoffstation',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Opypos\\V1\\Rest\\Pump\\PumpCollection' => array(
                'entity_identifier_name' => 'idopystation',
                'route_name' => 'opypos.rest.pump',
                'route_identifier_name' => 'idoffstation',
                'is_collection' => true,
            ),
            'Opypos\\V1\\Rest\\Pumpstatus\\PumpstatusEntity' => array(
                'entity_identifier_name' => 'idopystation',
                'route_name' => 'opypos.rest.pumpstatus',
                'route_identifier_name' => 'idoffstation',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Opypos\\V1\\Rest\\Pumpstatus\\PumpstatusCollection' => array(
                'entity_identifier_name' => 'idopystation',
                'route_name' => 'opypos.rest.pumpstatus',
                'route_identifier_name' => 'idoffstation',
                'is_collection' => true,
            ),
            'Opypos\\V1\\Rest\\Client\\ClientEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'opypos.rest.client',
                'route_identifier_name' => 'client_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Opypos\\V1\\Rest\\Client\\ClientCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'opypos.rest.client',
                'route_identifier_name' => 'client_id',
                'is_collection' => true,
            ),
            'Opypos\\V1\\Rest\\Closest\\ClosestEntity' => array(
                'entity_identifier_name' => 'idopystation',
                'route_name' => 'opypos.rest.closest',
                'route_identifier_name' => 'idoffstation',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Opypos\\V1\\Rest\\Closest\\ClosestCollection' => array(
                'entity_identifier_name' => 'idopystation',
                'route_name' => 'opypos.rest.closest',
                'route_identifier_name' => 'idoffstation',
                'is_collection' => true,
            ),
            'Opypos\\V1\\Rest\\Station\\StationEntity' => array(
                'entity_identifier_name' => 'idstation',
                'route_name' => 'opypos.rest.station',
                'route_identifier_name' => 'idstation',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'Opypos\\V1\\Rest\\Station\\StationCollection' => array(
                'entity_identifier_name' => 'idstation',
                'route_name' => 'opypos.rest.station',
                'route_identifier_name' => 'idstation',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(
            'Opypos\\V1\\Rest\\Configuration\\Controller' => array(
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
            'Opypos\\V1\\Rpc\\Ping\\Controller' => array(
                'actions' => array(
                    'Ping' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Opypos\\V1\\Rest\\Pump\\Controller' => array(
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
            'Opypos\\V1\\Rest\\Price\\Controller' => array(
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
            'Opypos\\V1\\Rest\\Station\\Controller' => array(
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
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Opypos\\V1\\Rpc\\Ping\\Controller' => 'Opypos\\V1\\Rpc\\Ping\\PingControllerFactory',
            'Opypos\\V1\\Rpc\\MonitorRaisePump\\Controller' => 'Opypos\\V1\\Rpc\\MonitorRaisePump\\MonitorRaisePumpControllerFactory',
            'Opypos\\V1\\Rpc\\MonitorFuelPumped\\Controller' => 'Opypos\\V1\\Rpc\\MonitorFuelPumped\\MonitorFuelPumpedControllerFactory',
            'Opypos\\V1\\Rpc\\MonitorHangPump\\Controller' => 'Opypos\\V1\\Rpc\\MonitorHangPump\\MonitorHangPumpControllerFactory',
        ),
    ),
    'zf-rpc' => array(
        'Opypos\\V1\\Rpc\\Ping\\Controller' => array(
            'service_name' => 'Ping',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'opypos.rpc.ping',
        ),
        'Opypos\\V1\\Rpc\\MonitorRaisePump\\Controller' => array(
            'service_name' => 'MonitorRaisePump',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'opypos.rpc.monitor-raise-pump',
        ),
        'Opypos\\V1\\Rpc\\MonitorFuelPumped\\Controller' => array(
            'service_name' => 'MonitorFuelPumped',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'opypos.rpc.monitor-fuel-pumped',
        ),
        'Opypos\\V1\\Rpc\\MonitorHangPump\\Controller' => array(
            'service_name' => 'MonitorHangPump',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'opypos.rpc.monitor-hang-pump',
        ),
    ),
);
