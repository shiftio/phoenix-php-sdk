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

}