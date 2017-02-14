<?php
$env = getenv('APPLICATION_ENV') ?: 'production';

return array(
    'module_listener_options' => array(
        'config_glob_paths' => array(
            sprintf('////openyapi/config/autoload/{,*.}{global,local,%s}.php', $env)
        ),
    ),
);
