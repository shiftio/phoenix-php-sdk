<?php

namespace mediasilo\auth;
require 'vendor/autoload.php';

use \OAuthStore;
use \OAuthRequester;
use mediasilo\http\oauth\TwoLeggedOauthClient;
use mediasilo\MediaSiloAPI;

$key = '8f2454db-f154-422b-a2d9-73d357fc3c0b'; // this is your consumer key
$secret = 'd5b0dac3-fe65-4c8b-8537-450ac431c070'; // this is your secret key
$baseUrl = "http://localhost:8082/v3";

$mediasiloApi = MediaSiloAPI::createFromApplicationConsumer($key, $secret, $baseUrl);

$mediasiloApi->updateQuickLink("532b323231da5f5ee4573a73", "Noob", null, null, null);
#$twoLeggedOauth = new TwoLeggedOauthClient($key, $secret, "http://localhost:8082/v3");

#$twoLeggedOauth->get("quicklinks/532846e631da43d01d969a04");
