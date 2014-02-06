<?php

if (!function_exists('curl_init')) {
    throw new Exception('We need cURL for the API to work. Have a look here: http://us3.php.net/curl');
}

if (!function_exists('json_decode')) {
    throw new Exception('We need json_decode for the API to work. If you\'re running a linux distro install this package: php-pecl-json');
}

require("SplClassLoader.php");

$classLoader = new SplClassLoader("mediasilo", __DIR__."/");
$classLoader->register();

$classLoader2 = new SplClassLoader(null, '/usr/share/php/PHPUnit');
$classLoader2->register();


