<?php
// Here you can initialize variables that will be available to your tests
use Codeception\Util\Fixtures;

// $url = 'http://';
$url = 'http://';

$clientData = array ('osversion' => 'Codeception ApiTest', 'lat' => '41.3941772', 'lng' => '2.2002508');
$userData = array ("email"=> "suombuel@gmail.com",
                   "password"=>"zaqweszz",
                   "first_name"=> "Test Codeception",
                   "last_name"=> "ApiTest",
                   "phone_number"=>"12341234");
$loginData = array (
    "username"=>"suombuel@gmail.com",
    "password"=>"zaqweszz",
    "grant_type"=>"password"     
);

// --- set Fixtures ---//
Fixtures::add('url', $url);
Fixtures::add('clientData', $clientData);
Fixtures::add('userData', $userData);
Fixtures::add('loginData', $loginData);
