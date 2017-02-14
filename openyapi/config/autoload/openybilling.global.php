<?php


// README: After seeing http://framework.zend.com/manual/current/en/tutorials/config.advanced.html
//         and reading http://framework.zend.com/manual/current/en/modules/zend.mvc.services.html
//         and reading http://framework.zend.com/manual/current/en/modules/zend.session.manager.html#comment-1371624179
//         we decided to operate this way, cause "Merge" between global and local occurs before any Module load
//         then seems to not be possible to attach an "replace_on_merge"  behaviour to extinguished "EVENT_LOAD_MODULES_PRE" (@see http://akrabat.com/a-list-of-zf2-events)

if (file_exists(preg_replace('/global\.php$/','local.php',__FILE__)))
    return [];


defined('POLICY_BILLING_LOCAL_DATA') || define('POLICY_BILLING_LOCAL_DATA',1,TRUE);
defined('POLICY_BILLING_DB_DATA_IF_AVAILABLE') || define('POLICY_BILLING_DB_DATA_IF_AVAILABLE',2,TRUE);
defined('POLICY_BILLING_ALLWAYS_DB_DATA') || define('POLICY_BILLING_ALLWAYS_DB_DATA',3,TRUE);

$options = array(
    'billing' => [
        'companies' =>[
            1 => array(
                'billingName'    => 'billingName',
                'billingAddress' => 'billingAddress',
                'billingId'      => 'billingId',
                'billingWeb'     => 'billingWeb',
                'billingLogo'    => 'billingLogo',
                'billingMail'    => 'billingMail',
                'billingPhone'   => '',
            ),
        ],
        'policies' => [
            POLICY_BILLING_LOCAL_DATA,
            POLICY_BILLING_DB_DATA_IF_AVAILABLE,
            POLICY_BILLING_ALLWAYS_DB_DATA
        ],
        'invoices' => [
                'policy' => POLICY_BILLING_DB_DATA_IF_AVAILABLE,
                'company'=> 1,
        ],
        'receipts'=>[
                'policy' => POLICY_BILLING_ALLWAYS_DB_DATA,
                'company'=> 1,
        ]
    ],
);


return array(
		'openy' => $options,
);
