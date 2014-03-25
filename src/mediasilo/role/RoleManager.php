<?php

namespace mediasilo\role;

use mediasilo\http\exception\NotFoundException;
use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\role\Role;

class RoleManager
{

    private $roles;
    private $webClient;

    public function __construct($webClient)
    {
        $this->webClient = $webClient;
    }

    /**
     * Returns an array of user roles for the given projects
     * @param $projectId
     * @return Role;
     */
    public function getUserRoleForProject($projectId, $accountId)
    {
        if (isset($this->roles[$projectId])) {
            return $this->roles[$projectId];
        } else {
            $roleResults = json_decode($this->webClient->get(sprintf(MediaSiloResourcePaths::ME, $projectId)))->roles;

            for ($i = 0; $i < count($roleResults); $i++) {
                if ($roleResults[$i]->context == $projectId) {
                    $role = $this->roles[$projectId] = Role::fromJson(json_encode($roleResults[$i]));
                    break;
                }
            }

            if (empty($this->roles[$projectId])) {
                $role = $this->getUserAccountLevelRole($accountId);
            }

            return $role;
        }
    }

    /**
     * Returns an account level role for the given account ID. Users
     * @param $accounttId
     * @return Role;
     */
    public function getUserAccountLevelRole($accountId)
    {
        if (isset($this->roles[$accountId])) {
            return $this->roles[$accountId];
        } else {
            $rolesResult = json_decode($this->webClient->get('/me'))->roles;

            for ($i = 0; $i < count($rolesResult); $i++) {
                if ($rolesResult[$i]->context == $accountId) {
                    $role = new Role($rolesResult[$i]->context,
                        $rolesResult[$i]->description,
                        $rolesResult[$i]->displayName,
                        $rolesResult[$i]->id,
                        $rolesResult[$i]->permissionGroups);
                    break;
                }
            }

            $this->roles[$accountId] = $role;

            return $role;
        }
    }
}
