<?php
// This is global bootstrap for autoloading
// print_r(getenv('APPLICATION_ENV'));
// print_r($_SERVER);
// die;

if(in_array('APPLICATION_ENV=test', $_SERVER['argv']))
{
    putenv("APPLICATION_ENV=test");
//     unset($_SERVER['argv'][$_SERVER['argc']-1]);
}

if(in_array('generate:cest', $_SERVER['argv']) ||
    in_array('build', $_SERVER['argv'])
   )
    putenv("APPLICATION_ENV=test");
    
// print_r(getenv('APPLICATION_ENV'));
// print_r($_SERVER);


if(getenv('APPLICATION_ENV') != 'test')
    die("\n".'Please, set APPLICATION_ENV to test.'."\n".
        "If this is a CLI use APPLICATION_ENV=test". "\n\n");

// print_r($_SERVER);
// die;