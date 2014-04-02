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
        $clientResponse = $this->webClient->get(sprintf(MediaSiloResourcePaths::ME));
        $this->accountId = json_decode($clientResponse->getBody())->accountId;
    }

    /**
     * Returns an array of user roles for the given projects
     * @param $asset
     * @return Role;
     */
    public function getUserRoleForAsset($asset)
    {
        if (empty($this->roles[$asset->projectId])) {
            $clientResponse = $this->webClient->get(sprintf(MediaSiloResourcePaths::ME, $asset->projectId));
            var_dump($clientResponse);
            $roleResults = json_decode($clientResponse->getBody())->roles;

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
     * @param $accountId
     * @return Role;
     */
    public function getUserAccountLevelRole($accountId)
    {
        if (isset($this->roles[$accountId])) {
            return $this->roles[$accountId];
        } else {
            $clientResponse = $this->webClient->get('/me');
            $rolesResult = json_decode($clientResponse->getBody())->roles;

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
