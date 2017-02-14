<?php
return array(
    'Oauthreg\\V1\\Rpc\\VerifyEmail\\Controller' => array(
        'GET' => array(
            'description' => 'Verify the user email.',
            'request' => null,
            'response' => null,
        ),
        'description' => 'Verify email.',
        'POST' => array(
            'description' => 'Verify the user\'s email.',
            'response' => '{
   "result": "Result of email verification through activation link sent to the user\'s email."
}',
        ),
    ),
    'Oauthreg\\V1\\Rpc\\RecoverPassword\\Controller' => array(
        'GET' => array(
            'description' => null,
            'request' => null,
            'response' => null,
        ),
    ),
    'Oauthreg\\V1\\Rpc\\SetNewPassword\\Controller' => array(
        'GET' => array(
            'description' => null,
            'request' => null,
            'response' => null,
        ),
    ),
    'Oauthreg\\V1\\Rpc\\VerifySms\\Controller' => array(
        'description' => 'Verify SMS',
    ),
);
