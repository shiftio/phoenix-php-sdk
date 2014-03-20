<?php

namespace mediasilo\http\oauth;

use mediasilo\config\Config;
use \OAuthStore;
use \OAuthRequester;

class TwoLeggedOauthClient {

	private $consumerKey;
	private $consumerSecret;

	function __construct($consumerKey, $consumerSecret) {
		$this->consumerKey = $consumerKey;
		$this->consumerSecret = $consumerSecret;
	}

	public function getAccessToken($username, $password, $hostname) {
		$options = array( 'consumer_key' => $this->consumerKey, 'consumer_secret' => $this->consumerSecret );
		OAuthStore::instance("2Leg", $options );

		$method = "GET";
		$params = array('username' => $username, 'password'=>$password, 'hostname' => $hostname, 'grant_type' => 'password');

		try
		{
	        // Obtain a request object for the request we want to make
	        $request = new OAuthRequester(CONFIG::MEDIASILO_API_BASE_URL."/", $method, $params);

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
	}

	public function get($path, $params = array()) {
		$options = array( 'consumer_key' => $this->consumerKey, 'consumer_secret' => $this->consumerSecret );
		OAuthStore::instance("2Leg", $options );

		$method = "GET";

        // Obtain a request object for the request we want to make
        $request = new OAuthRequester((rtrim(CONFIG::MEDIASILO_API_BASE_URL, "/")."/".rtrim(ltrim($path, "/"))), $method, $params);

        // Sign the request, perform a curl request and return the results, 
        // throws OAuthException2 exception on an error
        // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
        $result = $request->doRequest();
echo $result['body']; 
        return $result['body'];
	}

}