<?php

namespace mediasilo\user;

use mediasilo\http\MediaSiloResourcePaths;

class UserProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    public function getUser($userId) {
        $clientResponse = $this->webClient->get(sprintf("%s/%s", MediaSiloResourcePaths::USERS, $userId));
        return User::fromJson($clientResponse->getBody());
    }

    public function createUser(User $user) {
        $result = json_decode($this->webClient->post(MediaSiloResourcePaths::USERS, $user->toJson()));
        $user->setId($result->id);
    }

    public function updateUser(User $user) {
        $this->webClient->put(MediaSiloResourcePaths::USERS, $user->toJson());
    }
}
