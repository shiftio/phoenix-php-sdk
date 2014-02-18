<?php

namespace mediasilo\role;

class RoleManager {
    
    private $roles;

    public function __construct() {
        
    }

    /**
     * Returns an array of user roles for the given projects
     * @param $projectId
     * @return array(Role);
     */
    public function getUserRoleForProject($projectId) {
        $projectIdArray = $projectId;
        if(!is_array($projectIdArray)) {
            $projectIdArray = array($projectId);
        }
    }

}