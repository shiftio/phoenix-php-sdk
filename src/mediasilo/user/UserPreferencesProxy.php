<?php

namespace mediasilo\user;

use mediasilo\http\MediaSiloResourcePaths;

class UserPreferencesProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    public function getPreferences($userId) {
        $clientResponse = $this->webClient->get(sprintf(MediaSiloResourcePaths::USER_PREFERENCES,$userId));
        return json_decode($clientResponse->getBody());
    }

    public function getPreference($userId, $preferenceKey) {
        $clientResponse = $this->webClient->get(sprintf(MediaSiloResourcePaths::USER_PREFERENCES."/%s",$userId, $preferenceKey));
        return json_decode($clientResponse->getBody());
    }

    public function updatePreference($userId, $preferenceName, $preferenceValue) {
        $preference = (object)array('userId' => $userId, 'name' => $preferenceName, 'value' => $preferenceValue);
        $this->webClient->put(sprintf(MediaSiloResourcePaths::USER_PREFERENCES,$userId), json_encode($preference));
    }

}