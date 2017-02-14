<?php
// Here you can initialize variables that will be available to your tests
use Codeception\Util\Fixtures;

// $installedPos = array (1, 1204, 9085);   // For pre-production machine ()
$installedPos = array (1, 9085);            // For test machine (apolo)
Fixtures::add('installedPos', $installedPos);

// $url = 'http://';
$url = 'http://';
Fixtures::add('url', $url);