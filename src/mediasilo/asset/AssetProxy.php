<?php

namespace mediasilo\asset;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\asset\Asset;
use mediasilo\role\RoleManager;
use mediasilo\http\exception\NotFoundException;

class AssetProxy {

    private $webClient;
    private $roleManager;

    public function __construct($webClient) {
        $this->webClient = $webClient;
        $this->roleManager = new RoleManager($this->webClient);
    }

    /**
     * Gets an exiting asset given an asset Id
     * @param $id
     * @return Asset
     */
    public function getAsset($id, $acl = false) {
        $asset = Asset::fromJson($this->webClient->get(MediaSiloResourcePaths::ASSETS . "/" . $id));
        if($acl == true) {
            $this->attachAclToAsset($asset);
        }

        return $asset;
    }

    /**
     * Gets multiple assets given asset Ids
     * @param $ids
     * @return Array(Asset)
     */
    public function getAssetsByProjectId($projectId, $acl = false) {
        $assets = array();
        
        $result = json_decode($this->webClient->get(sprintf(MediaSiloResourcePaths::PROJECT_ASSETS,$projectId)));
        $assetsResults = $result->results;

        if(!empty($assetsResults)) {
            foreach($assetsResults as $assetsResult) {
                $asset = Asset::fromStdClass($assetsResult);
                if($acl == true) {
                    $this->attachAclToAsset($asset);
                }
                array_push($assets, $asset);
            }
        }

        return $assets;
    }

    /**
     * Gets multiple assets given asset Ids
     * @param $ids
     * @return Array(Asset)
     */
    public function getAssetsByFolderId($folderId, $acl = false) {
        $assets = array();
        
        $result = json_decode($this->webClient->get(sprintf(MediaSiloResourcePaths::FOLDER_ASSETS,$folderId)));
        $assetsResults = $result->results;

        if(!empty($assetsResults)) {
            foreach($assetsResults as $assetsResult) {
                $asset = Asset::fromStdClass($assetsResult);
                if($acl == true) {
                    $this->attachAclToAsset($asset);
                }
                array_push($assets, $asset);
            }
        }

        return $assets;
    }

    private function attachAclToAsset(&$asset) {
        try {
            $role = $this->roleManager->getUserRoleForProject($asset->projectId);
            $asset->acl = $role->getPermissionGroups();
        } catch(NotFoundException $nfe) {}
    }

}