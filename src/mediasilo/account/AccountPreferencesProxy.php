<?php

namespace mediasilo\account;

use mediasilo\http\MediaSiloResourcePaths;

class AccountPreferencesProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    public function getPreferences()
    {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::MY_ACCOUNT_PREFERENCES);
        return json_decode($clientResponse->getBody());
    }

    public function getPreferencesByAccountId($accountId) {
        $clientResponse = $this->webClient->get(sprintf(MediaSiloResourcePaths::ACCOUNT_PREFERENCES,$accountId));
        return json_decode($clientResponse->getBody());
    }

}