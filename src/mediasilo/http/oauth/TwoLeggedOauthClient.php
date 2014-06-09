<?php

namespace mediasilo\http\oauth;

use mediasilo\http\exception\NotAuthenticatedException;
use mediasilo\http\exception\NotFoundException;
use mediasilo\http\exception\ConnectionException;
use mediasilo\http\exception\NotAuthorizedException;
use mediasilo\http\ratelimit\exception\RateLimitException;
use mediasilo\http\exception\ValidationException;
use mediasilo\http\WebClientResponse;
use \OAuthStore;
use \OAuthRequester;
use \OAuthHttpException;

class TwoLeggedOauthClient {

	public $consumerKey;
	public $consumerSecret;
	public $baseUrl;
	public $options;
	public $oauthWorkflow = '2Leg';

	function __construct($consumerKey, $consumerSecret, $baseUrl) {
		$this->consumerKey = $consumerKey;
		$this->consumerSecret = $consumerSecret;
        $this->baseUrl = $baseUrl;
	}

	public static function create2LegClient($consumerKey, $consumerSecret, $baseUrl = "phoenix.mediasilo.com/v3") {
		$instance = new self($consumerKey, $consumerSecret, $baseUrl);
		$instance->options = array( 'consumer_key' => $consumerKey, 'consumer_secret' => $consumerSecret);
		OAuthStore::instance($instance->oauthWorkflow, $instance->options, true);

		return $instance;
	}

	public static function create2LegProxyCredsClient($consumerKey, $consumerSecret, $accessToken, $baseUrl = "phoenix.mediasilo.com/v3") {
		$instance = new self($consumerKey, $consumerSecret, $baseUrl);
		$instance->setOauthWorkflow('2LegProxyCreds');
		$instance->options = array(
			'consumer_key' => $consumerKey,
			'consumer_secret' => $consumerSecret,
			'access_token' => $accessToken);

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
        $request = new OAuthRequester((rtrim($this->baseUrl, "/")."/".rtrim(ltrim($path, "/"))), $method, $params);

        // Sign the request, perform a curl request and return the results,
        // throws OAuthException2 exception on an error
        // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
        try {
            $result = $request->doRequest();
            return $result['body'];
        } catch (OAuthHttpException $e) {
            $this->parseException($e);
        }
	}

	public function get($path, $params = array()) {
		OAuthStore::instance($this->oauthWorkflow, $this->options, true);

		$method = "GET";

        // Obtain a request object for the request we want to make
        $request = new OAuthRequester((rtrim($this->baseUrl, "/")."/".rtrim(ltrim($path, "/"))), $method, $params);

        // Sign the request, perform a curl request and return the results, 
        // throws OAuthException2 exception on an error
        // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
        try {
            $result = $request->doRequest();
            return new WebClientResponse($result['body'], $result['headers'], $result['code']);
        } catch (OAuthHttpException $e) {
            $this->parseException($e);
        }
	}

	public function post($path, $payload) {
		$method = "POST";

        // Obtain a request object for the request we want to make
        $request = new OAuthRequester((rtrim($this->baseUrl, "/")."/".rtrim(ltrim($path, "/"))), $method, null, $payload);

        // Sign the request, perform a curl request and return the results, 
        // throws OAuthException2 exception on an error
        // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
        try {
            $result = $request->doRequest();
            return $result['body'];
        } catch (OAuthHttpException $e) {
            $this->parseException($e);
        }
	}

	public function put($path, $payload) {
		$method = "PUT";

        // Obtain a request object for the request we want to make
        $request = new OAuthRequester((rtrim($this->baseUrl, "/")."/".rtrim(ltrim($path, "/"))), $method, null, $payload);

        // Sign the request, perform a curl request and return the results, 
        // throws OAuthException2 exception on an error
        // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
        try {
            $result = $request->doRequest();
            return $result['body'];
        } catch (OAuthHttpException $e) {
            $this->parseException($e);
        }
	}

	public function delete($path) {
		$method = "DELETE";

        // Obtain a request object for the request we want to make
        $request = new OAuthRequester((rtrim($this->baseUrl, "/")."/".rtrim(ltrim($path, "/"))), $method, $params);

        // Sign the request, perform a curl request and return the results, 
        // throws OAuthException2 exception on an error
        // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
        try {
            $result = $request->doRequest();
            return $result['body'];
        } catch (OAuthHttpException $e) {
            $this->parseException($e);
        }
	}

    private function parseException($exception) {
        $message = $exception->getMessage();
        preg_match('/Request failed with code ([0-9]{3}):/', $message, $matches);
        $errorCode = $matches[1];

        if($errorCode == 429) {
            throw new RateLimitException("Your API rate limit has been exceeded", json_decode($message));
        }
        if($errorCode == 400) {
            throw new ValidationException("The request was invalid. Review the error collection to see what the problem was.", json_decode($message));
        }
        if($errorCode == 401) {
            throw new NotAuthenticatedException("You are not authorized to perform this request", json_decode($message));
        }
        if($errorCode == 403) {
            throw new NotAuthorizedException("You are not authorized to perform this request", json_decode($message));
        }
        if($errorCode == 404) {
            throw new NotFoundException("There was no resource matching the request.", json_decode($message));
        }
        if($errorCode == 0) {
            throw new ConnectionException("There was a problem connecting to the MediaSilo API", json_decode($message));
        }
    }
}
