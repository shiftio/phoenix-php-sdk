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
     * @param String $id
     * @param Bool $acl
     * @return Asset
     */
    public function getAsset($id, $acl = false) {
        if ($acl) {
            $accountId = json_decode($this->webClient->get(sprintf(MediaSiloResourcePaths::ME)))->accountId;
        }

        $asset = Asset::fromJson($this->webClient->get(MediaSiloResourcePaths::ASSETS . "/" . $id));

        if($acl == true) {
            $this->attachAclToAsset($asset, $accountId);
        }

        return $asset;
    }

    /**
     * Gets an exiting asset given an asset Id
     * @param Array $ids - Array of Asset IDs to fetch
     * @param Bool $acl - True to include acl hash on asset object
     * @return Array(Asset)
     */
    public function getAssetByIds(array $ids, $acl = false) {
        $assets = array();
        $idList = implode(',', $ids);
        $results = json_decode($this->webClient->get(sprintf("%s?ids=%s",MediaSiloResourcePaths::ASSETS,$idList)));

        if ($acl) {
            $accountId = json_decode($this->webClient->get(sprintf(MediaSiloResourcePaths::ME)))->accountId;
        }

        if(!empty($results)) {
            foreach($results as $assetsResult) {
                $asset = Asset::fromStdClass($assetsResult);
                if($acl == true) {
                    $this->attachAclToAsset($asset, $accountId);
                }
                array_push($assets, $asset);
            }
        }
        return $assets;
    }

    /**
     * Gets multiple assets given asset Ids
     * @param String $projectId
     * @param Bool $acl - True to include acl hash on asset object
     * @return Array(Asset)
     */
    public function getAssetsByProjectId($projectId, $acl = false) {
        $assets = array();

        if ($acl) {
            $accountId = json_decode($this->webClient->get(sprintf(MediaSiloResourcePaths::ME)))->accountId;
        }

        $assetsResults = json_decode($this->webClient->get(sprintf(MediaSiloResourcePaths::PROJECT_ASSETS, $projectId)));

        if(!empty($assetsResults)) {
            foreach($assetsResults as $assetResult) {
                $asset = Asset::fromStdClass($assetResult);
                if($acl == true) {
                    $this->attachAclToAsset($asset, $accountId);
                }
                array_push($assets, $asset);
            }
        }



        return $assets;
    }

    /**
     * Gets multiple assets given asset Ids
     * @param String $folderId
     * @param Bool $acl
     * @return Array(Asset)
     */
    public function getAssetsByFolderId($folderId, $acl = false) {
        $assets = array();

        if ($acl) {
            $accountId = json_decode($this->webClient->get(sprintf(MediaSiloResourcePaths::ME)))->accountId;
        }

        $result = json_decode($this->webClient->get(sprintf(MediaSiloResourcePaths::FOLDER_ASSETS,$folderId)));
        $assetsResults = $result->results;

        if(!empty($assetsResults)) {
            foreach($assetsResults as $assetsResult) {
                $asset = Asset::fromStdClass($assetsResult);
                if($acl == true) {
                    $this->attachAclToAsset($asset, $accountId);
                }
                array_push($assets, $asset);
            }
        }

        return $assets;
    }

    private function attachAclToAsset(&$asset, &$accountId) {
        $role = $this->roleManager->getUserRoleForProject($asset->projectId, $accountId);
        $asset->acl = $role->getPermissionGroups();
    }

}
