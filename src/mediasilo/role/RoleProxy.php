<?php

namespace mediasilo\role;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\role\Role;

class RoleProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Gets 
     * @param $id
     * @return Asset
     */
    public function getUserRoleForProject($projectId) {
        //$asset = Asset::fromJson($this->webClient->get(MediaSiloResourcePaths::ASSETS . "/" . $id));
    }

}