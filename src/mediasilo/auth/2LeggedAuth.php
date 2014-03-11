<?php

namespace mediasilo\auth;

require "/var/www/phoenix-php-sdk/src/mediasilo/oauth-php/library/OAuthStore.php";
require "/var/www/phoenix-php-sdk/src/mediasilo/oauth-php/library/OAuthRequester.php";

use OAuthStore;
use OAuthRequester;

$key = 'FOO'; // this is your consumer key
$secret = 'BAR'; // this is your secret key

$options = array( 'consumer_key' => $key, 'consumer_secret' => $secret );
OAuthStore::instance("2Leg", $options );

$url = "http://localhost:8082/v3/oauthtest"; // this is the URL of the request
$method = "GET"; // you can also use POST instead
$params = array('username' => 'user', 'password'=>'user', 'hostname' => 'cloudcompanion', 'grant_type' => 'password');

try
{
        // Obtain a request object for the request we want to make
        $request = new OAuthRequester($url, $method, $params);

        // Sign the request, perform a curl request and return the results, 
        // throws OAuthException2 exception on an error
        // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
        $result = $request->doRequest();
        
        echo $result;
        echo "\n";
        $response = $result['body'];
        echo $response;
        echo "\n";
}
catch(OAuthException2 $e)
{

}