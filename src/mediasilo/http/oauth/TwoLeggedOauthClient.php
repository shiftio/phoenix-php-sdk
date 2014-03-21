<?php

namespace mediasilo\http\oauth;

use mediasilo\config\Config;
use \OAuthStore;
use \OAuthRequester;

class TwoLeggedOauthClient {

	public $consumerKey;
	public $consumerSecret;
	public $baseUrl;
	public $options;
	public $oauthWorkflow = '2Leg';

	function __construct($consumerKey, $consumerSecret) {
		$this->consumerKey = $consumerKey;
		$this->consumerSecret = $consumerSecret;
	}

	public static function create2LegClient($consumerKey, $consumerSecret, $baseUrl = "phoenix.mediasilo.com/v3") {
		$instance = new self($consumerKey, $consumerSecret);
		$instance->consumerKey = $consumerKey;
		$instance->consumerSecret = $consumerSecret;
		$instance->baseUrl = $baseUrl;
		$instance->options = array( 'consumer_key' => $consumerKey, 'consumer_secret' => $consumerSecret);
		OAuthStore::instance($instance->oauthWorkflow, $instance->options, true);

		return $instance;
	}

	public static function create2LegProxyCredsClient($consumerKey, $consumerSecret, $accessToken, $baseUrl = "phoenix.mediasilo.com/v3") {
		$instance = new self($consumerKey, $consumerSecret);
		$instance->setOauthWorkflow('2LegProxyCreds');
		$instance->consumerKey = $consumerKey;
		$instance->consumerSecret = $consumerSecret;
		$instance->baseUrl = $baseUrl;
		$instance->options = array( 'consumer_key' => $consumerKey, 'consumer_secret' => $consumerSecret, 'access_token' => $accessToken);

		OAuthStore::instance($instance->oauthWorkflow, $instance->options, true);

		return $instance;
	}

	public function addOption($key, $val) {
		$this->options[$key] = $val;
	}

	public function setOauthWorkflow($oauthWorkflow) {
		$this->oauthWorkflow = $oauthWorkflow;
	}

	public function getAccessToken($params) {
		$path = "/";
		$method = "GET";

        // Obtain a request object for the request we want to make
        $request = new OAuthRequester((rtrim($this->baseUrl, "/")."/".rtrim(ltrim($path, "/"))), $method, $params);

        // Sign the request, perform a curl request and return the results, 
        // throws OAuthException2 exception on an error
        // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
        $result = $request->doRequest();
        return $result['body'];
	}

	public function get($path, $params = array()) {
		OAuthStore::instance($this->oauthWorkflow, $this->options, true);

		$method = "GET";

        // Obtain a request object for the request we want to make
        $request = new OAuthRequester((rtrim($this->baseUrl, "/")."/".rtrim(ltrim($path, "/"))), $method, $params);

        // Sign the request, perform a curl request and return the results, 
        // throws OAuthException2 exception on an error
        // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
        $result = $request->doRequest();
        return $result['body'];
	}

	public function post($path, $payload) {
		$method = "POST";

        // Obtain a request object for the request we want to make
        $request = new OAuthRequester((rtrim($this->baseUrl, "/")."/".rtrim(ltrim($path, "/"))), $method, null, $payload);

        // Sign the request, perform a curl request and return the results, 
        // throws OAuthException2 exception on an error
        // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
        $result = $request->doRequest();
        return $result['body'];
	}

	public function put($path, $payload) {
		$method = "PUT";

        // Obtain a request object for the request we want to make
        $request = new OAuthRequester((rtrim($this->baseUrl, "/")."/".rtrim(ltrim($path, "/"))), $method, null, $payload);

        // Sign the request, perform a curl request and return the results, 
        // throws OAuthException2 exception on an error
        // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
        $result = $request->doRequest();
        return $result['body'];
	}

	public function delete($path) {
		$method = "DELETE";

        // Obtain a request object for the request we want to make
        $request = new OAuthRequester((rtrim($this->baseUrl, "/")."/".rtrim(ltrim($path, "/"))), $method, $params);

        // Sign the request, perform a curl request and return the results, 
        // throws OAuthException2 exception on an error
        // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
        $result = $request->doRequest();
        return $result['body'];
	}
}