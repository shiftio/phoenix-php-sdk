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
    private $accountId;

    public function __construct($webClient)
    {
        $this->webClient = $webClient;
        $this->accountId = json_decode($this->webClient->get(sprintf(MediaSiloResourcePaths::ME)))->accountId;
    }

    /**
     * Returns an array of user roles for the given projects
     * @param $projectId
     * @return Role;
     */
    public function getUserRoleForAsset($asset)
    {
        if (empty($this->roles[$asset->projectId])) {
            $roleResults = json_decode($this->webClient->get(sprintf(MediaSiloResourcePaths::ME, $asset->projectId)))->roles;

            for ($i = 0; $i < count($roleResults); $i++) {
                if ($roleResults[$i]->context == $asset->projectId) {
                    $this->roles[$asset->projectId] = Role::fromJson(json_encode($roleResults[$i]));
                    $role = $this->roles[$asset->projectId];
                    break;
                }
            }
        } else {
            $role = $this->roles[$asset->projectId];
        }

        if (empty($role)) {
            if (empty($this->roles[$this->accountId])) {
                $role = $this->getUserAccountLevelRole($this->accountId);
            } else {
                $role = $this->roles[$this->accountId];
            }
        }

        if (empty($role)) {
            $role = new Role();
        }

        return $role;
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
