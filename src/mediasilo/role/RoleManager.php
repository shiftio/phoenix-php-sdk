<?php

namespace mediasilo\role;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\role\Role;

class RoleManager {
    
    private $roles;
    private $webClient;

    public function __construct(WebClient $webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Returns an array of user roles for the given projects
     * @param $projectId
     * @return Role;
     */
    public function getUserRoleForProject($projectId) {
        if(isset($roles[$projectId])) {
            return $roles[$projectId];
        } else {
            $role = Role::fromJson($this->webClient->get(sprintf(MediaSiloResourcePaths::USER_PROJECT_ROLES, $projectId)));
            $roles[$projectId] = $role;
            return $role;
        }
    }

}