<?php

namespace mediasilo\account;

use mediasilo\http\MediaSiloResourcePaths;

class AccountPreferencesProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    public function getPreferences($accountId) {
        $clientResponse = $this->webClient->get(sprintf(MediaSiloResourcePaths::ACCOUNT_PREFERENCES,$accountId));
        return json_decode($clientResponse->getBody());
    }

    public function getPreference($accountId, $preferenceKey) {
        $clientResponse = $this->webClient->get(sprintf(MediaSiloResourcePaths::ACCOUNT_PREFERENCES."/%s",$accountId, $preferenceKey));
        return json_decode($clientResponse->getBody());
    }

    public function updatePreference($accountId, $preferenceName, $preferenceValue) {
        $preference = (object)array('accountId' => $accountId, 'name' => $preferenceName, 'value' => $preferenceValue);
        $this->webClient->put(sprintf(MediaSiloResourcePaths::ACCOUNT_PREFERENCES,$accountId), json_encode($preference));
    }

}