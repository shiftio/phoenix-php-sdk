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
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::ASSETS . "/" . $id);
        $asset = Asset::fromJson($clientResponse->getBody());

        if($acl == true) {
            $this->attachAclToAsset($asset);
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
        $clientResponse = $this->webClient->get(sprintf("%s?ids=%s",MediaSiloResourcePaths::ASSETS,$idList));
        $results = json_decode($clientResponse->getBody());

        if(!empty($results)) {
            foreach($results as $assetsResult) {
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
     * @param String $projectId
     * @param Bool $acl - True to include acl hash on asset object
     * @return Array(Asset)
     */
    public function getAssetsByProjectId($projectId, $acl = false) {
        $assets = array();

        $clientResponse = $this->webClient->get(sprintf(MediaSiloResourcePaths::PROJECT_ASSETS, $projectId));
        $assetsResults = json_decode($clientResponse->getBody($clientResponse));

        if(!empty($assetsResults)) {
            foreach($assetsResults as $assetResult) {
                $asset = Asset::fromStdClass($assetResult);
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
     * @param String $folderId
     * @param Bool $acl
     * @return Array(Asset)
     */
    public function getAssetsByFolderId($folderId, $acl = false) {
        $assets = array();

        $clientResponse = $this->webClient->get(sprintf(MediaSiloResourcePaths::FOLDER_ASSETS,$folderId));
        $assetResults = json_decode($clientResponse->getBody($clientResponse));

        if(!empty($assetResults)) {
            foreach($assetResults as $assetResult) {
                $asset = Asset::fromStdClass($assetResult);

                if ($acl == true) {
                    $this->attachAclToAsset($asset);
                }

                array_push($assets, $asset);
            }
        }

        return $assets;
    }

    private function attachAclToAsset(&$asset) {
        $role = $this->roleManager->getUserRoleForAsset($asset);
        $asset->acl = $role->getPermissionGroups();
    }

}
