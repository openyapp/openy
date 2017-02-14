<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'SOAP\Server' => 'TpvVirtual\Controller\SOAPServerController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'tpvvirtual.soap.server' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/soap',
                    'defaults' => array(
                        'controller' => 'SOAP\Server',
                        'action' => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'tpvvirtual.soap.server.wsdl' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/wsdl',
                            'defaults' => array(
                                'controller' => 'SOAP\Server',
                                'action' => 'wsdl'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
            'template_path_stack' => array(
                'tpv-virtual' => __DIR__ . '/../view',
            ),
            'template_map' => array(
                'wsdl'           => __DIR__ . '/../view/tpv-virtual/soap-server/SerClsWSEntrada.wsdl',
            ),
            'strategies' => array(
                'ViewXmlStrategy'
            ),

    ),
);