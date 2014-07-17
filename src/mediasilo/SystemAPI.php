<?php

namespace mediasilo;

use mediasilo\MediaSiloAPI;
use mediasilo\http\WebClient;
use mediasilo\http\oauth\TwoLeggedOauthClient;
use mediasilo\http\oauth\OAuthException;
use mediasilo\config\Meta;

use mediasilo\account\AccountPreferencesProxy;

class SystemAPI extends MediaSiloAPI
{

    public function __construct() {}

    public static function createFromHostCredentials($username, $password, $hostname, $baseUrl = Meta::API_ROOT_URL) {
        $instance = new self();
        $instance->webClient = WebClient::createFromHostCredentials($username, $password, $hostname, $baseUrl);
        $instance->init();
        $instance->me();
        return $instance;
    }

    public static function createFromSession($session, $host, $baseUrl = Meta::API_ROOT_URL) {
        $instance = new self();
        $instance->webClient = WebClient::createFromSession($session, $host, $baseUrl);
        $instance->init();
        $instance->me();

        return $instance;
    }

    public static function createFromApplicationConsumer($consumerKey, $consumerSecret, $baseUrl = Meta::API_ROOT_URL) {
        $instance = new self();
        $instance->consumerKey = $consumerKey;
        $instance->consumerSecret = $consumerSecret;
        $instance->baseUrl = $baseUrl;
        $instance->webClient = TwoLeggedOauthClient::create2LegClient($consumerKey, $consumerSecret, $baseUrl);
        $instance->init();

        return $instance;
    }

    public function getAccessToken($username, $password, $hostname) {
        $params = array('username' => $username, 'password'=>$password, 'hostname' => $hostname, 'grant_type' => 'password');
        $response = json_decode($this->webClient->getAccessToken($params));
        $this->webClient = WebClient::createFromHostCredentials($username, $password, $hostname, null);
        $this->me();
        $this->webClient = TwoLeggedOauthClient::create2LegProxyCredsClient($this->consumerKey, $this->consumerSecret, $response->id, $this->baseUrl);
        $this->proxyInit();

        return $response->id;
    }

    public function getAccessTokenBySession($sessionKey, $hostname) {
        $params = array('session' => $sessionKey, 'hostname' => $hostname, 'grant_type' => 'password');
        $response = json_decode($this->webClient->getAccessToken($params));
        $this->webClient = WebClient::createFromSession($sessionKey, $hostname, null);
        $this->me();
        $this->webClient = TwoLeggedOauthClient::create2LegProxyCredsClient($this->consumerKey, $this->consumerSecret, $response->id, $this->baseUrl);
        $this->proxyInit();

        return $response->id;
    }

    public function setAccessToken($accessToken) {
        if(!isset($this->consumerKey)) {
            throw new OAuthException("There is no consumer credentials set for the API instance. An access token cannot be used without consumer credentials.");
        }
        $this->webClient = TwoLeggedOauthClient::create2LegProxyCredsClient($this->consumerKey, $this->consumerSecret, $accessToken, $this->baseUrl);
        $this->proxyInit();
    }

    public function unsetAccessToken() {
        if(!isset($this->consumerKey)) {
            throw new OAuthException("There is no consumer credentials set for the API instance. An access token cannot be used without consumer credentials.");
        }
        $this->webClient = TwoLeggedOauthClient::create2LegClient($this->consumerKey, $this->consumerSecret, $this->baseUrl);
        $this->proxyInit();
    }

    /**
     * Get all preferences defined for ANY account. !!!Internal Use Only!!!.
     * @param $accountId
     * @return mixed
     */
    public function getAccountPreferencesForAccount($accountId) {
        return $this->accountPreferencesProxy->getPreferences($accountId);
    }

    /**
     * Get a list of tracked events specified by the events list and filtered by a query
     * @param Array $events
     * @param String $query
     * @return Array[Object]
     */
    public function getAnalytics($events, $query)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::ANALYTICS_SPECIFIC, join(",", $events));
        $clientResponse = json_decode($this->webClient->post($resourcePath, $query));
        return $clientResponse;
    }

    /**
     * Performs a Password Reset Request (sends password reset link with token to user's email)
     *
     * @requires System Permission
     * @param $hostname
     * @param $username
     * @param string $type (optional)
     * @param null $redirectUri (optional)
     * @returns Object - ID property contains request token id
     */
    public function initiatePasswordReset($hostname, $username, $type = "reset", $redirectUri = null) {
        $request = new PasswordResetRequest($hostname, $username, $type, $redirectUri);
        return json_decode($this->webClient->post(MediaSiloResourcePaths::PASSWORD_RESET, $request->toJson()));
    }

    /**
     * Validates a Password Reset Request token is still valid
     * @requires System Permission
     * @param String $token
     * @return Object - Reset Token Representation
     */
    public function validateResetToken($token) {
        $resourcePath = sprintf("%s/%s", MediaSiloResourcePaths::PASSWORD_RESET, $token);
        $clientResponse = $this->webClient->get($resourcePath);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Performs a password update for a user associated with a valid token
     * * @requires System Permission
     * @param String $token
     * @param String $password
     * @return Object - redirectUrl property contains location to redirect to upon success
     */
    public function processPasswordReset($token, $password) {
        $request = new PasswordReset($token, $password);
        return json_decode($this->webClient->put(MediaSiloResourcePaths::PASSWORD_RESET, $request->toJson()));
    }

    /**
     * Gets a User Key/Value Pair By UUID
     * @param String $id
     * @return Object
     */
    public function getUserKeyPair($id)
    {
        $clientResponse = $this->webClient->get($this->webClient->get(MediaSiloResourcePaths::USER_LOOKUPS . "/" . $id));
        return json_decode($clientResponse->getBody());
    }

    /**
     * Gets a list of User Key/Value Pairs
     * @return Array[Object]
     */
    public function getUserKeyPairs()
    {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::USER_LOOKUPS);
        return json_decode($clientResponse->getBody());
    }

}