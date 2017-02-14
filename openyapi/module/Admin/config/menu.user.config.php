<?php
return array(		
   	
//     array(
//         'label' => 'Api documentation',
//         'route' => 'zf-apigility/swagger'
//     ),
    
    array(
        'label' => 'Documentation',
        'route' => 'success',
        'action' => 'index',
    ),
    array(
        'label' => 'Logout',
        'route' => 'login/process',
        'action' => 'logout',
    ),
    array(
        'label' => 'Monitor',
        'route' => 'monitor',
        'action' => 'index',
    ),
);
