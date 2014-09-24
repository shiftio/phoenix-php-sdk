<?php

namespace mediasilo\asset;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\asset\Asset;
use mediasilo\role\RoleManager;
use mediasilo\http\exception\NotFoundException;
use stdClass;

class AssetProxy {

    private $webClient;
    private $roleManager;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Gets an exiting asset given an asset Id
     * @param String $id
     * @param Bool $acl
     * @return Asset
     */
    public function getAsset($id, $acl = false) {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::ASSETS . "/" . $id)->getBody();
        $asset = Asset::fromJson($clientResponse);

        if($acl) {
            $acl = Asset::convertAssetPermissionsArrayToAcl($clientResponse->permissions);
            $asset->setAcl($acl);
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
                if($acl) {
                    $acl = Asset::convertAssetPermissionsArrayToAcl($assetsResult->permissions);
                    $asset->setAcl($acl);
                }
                array_push($assets, $asset);
            }
        }
        return $assets;
    }

    /**
     * Gets an exiting asset given search an array of search parameters
     * @param Array $searchParams - Array of search parameters
     * @param Bool $acl - Array of search parameters
     * @param Bool $wrapPagination
     * @return Array(Asset)
     */
    public function getAssets($searchParams, $acl = false, $wrapPagination = false) {
        $assets = array();
        $searchQuery = '?';

        foreach ($searchParams as $key => $value) {
            $searchQuery .= $key . '=' . $value . '&';
        }

        $searchQuery = substr($searchQuery, 0, -1);
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::ASSETS . $searchQuery);
        $assetsResults = json_decode($clientResponse->getBody());

        if (!empty($assetsResults)) {
            foreach($assetsResults as $assetResult) {
                $asset = Asset::fromStdClass($assetResult);

                if($acl) {
                    $acl = Asset::convertAssetPermissionsArrayToAcl($assetResult->permissions);
                    $asset->setAcl($acl);
                }

                array_push($assets, $asset);
            }
        }


        if ($wrapPagination) {
            $response = new stdClass();
            $response->paging = new stdClass();

            try {
                foreach($clientResponse->getHeaders() as $header) {
                    $parts = explode(":", $header);
                    if ($parts[0] == 'total-results') {
                        $response->paging->total = intval($parts[1]);
                    }
                }
            } catch (\Exception $e) {
                $response->paging->total = 0;
            }

            $response->results = $assets;
            return $response;

        } else {
            return $assets;
        }
    }

    /**
     * Gets multiple assets given Project Id
     * @param String $projectId
     * @param Bool $acl - True to include acl hash on asset object
     * @param Array $searchParams - Array of search parameters
     * @param Bool $wrapPagination
     * @return Array(Asset)
     */
    public function getAssetsByProjectId($projectId, $acl = false, $searchParams = array(), $wrapPagination = false) {
        $assets = array();
        $searchQuery = '?';

        foreach ($searchParams as $key => $value) {
            $searchQuery .= $key . '=' . $value . '&';
        }
        $searchQuery = substr($searchQuery, 0, -1);
        $clientResponse = $this->webClient->get(sprintf(MediaSiloResourcePaths::PROJECT_ASSETS, $projectId) . $searchQuery);
        $assetsResults = json_decode($clientResponse->getBody());

        if (!empty($assetsResults)) {
            foreach($assetsResults as $assetResult) {
                $asset = Asset::fromStdClass($assetResult);

                if($acl) {
                    $acl = Asset::convertAssetPermissionsArrayToAcl($assetResult->permissions);
                    $asset->setAcl($acl);
                }

                array_push($assets, $asset);
            }
        }

        if ($wrapPagination) {
            $response = new stdClass();
            $response->paging = new stdClass();

            try {
                foreach($clientResponse->getHeaders() as $header) {
                    $parts = explode(":", $header);
                    if ($parts[0] == 'total-results') {
                        $response->paging->total = intval($parts[1]);
                    }
                }
            } catch (\Exception $e) {
                $response->paging->total = 0;
            }

            $response->results = $assets;
            return $response;

        } else {
            return $assets;
        }
    }

    /**
     * Gets multiple assets given Folder Id
     * @param String $folderId
     * @param Bool $acl - True to include acl hash on asset object
     * @param Array $searchParams - Array of search parameters
     * @param Bool $wrapPagination
     * @return Array(Asset)
     */
    public function getAssetsByFolderId($folderId, $acl = false, $searchParams = array(), $wrapPagination = false) {
        $assets = array();
        $searchQuery = '?';

        foreach ($searchParams as $key => $value) {
            $searchQuery .= $key . '=' . $value . '&';
        }

        $searchQuery = substr($searchQuery, 0, -1);
        $clientResponse = $this->webClient->get(sprintf(MediaSiloResourcePaths::FOLDER_ASSETS, $folderId) . $searchQuery);
        $assetResults = json_decode($clientResponse->getBody());

        if(!empty($assetResults)) {
            foreach($assetResults as $assetResult) {
                $asset = Asset::fromStdClass($assetResult);

                if ($acl) {
                    $acl = Asset::convertAssetPermissionsArrayToAcl($assetResult->permissions);
                    $asset->setAcl($acl);
                }

                array_push($assets, $asset);
            }
        }

        if ($wrapPagination) {
            $response = new stdClass();
            $response->paging = new stdClass();

            try {
                foreach($clientResponse->getHeaders() as $header) {
                    $parts = explode(":", $header);
                    if ($parts[0] == 'total-results') {
                        $response->paging->total = intval($parts[1]);
                    }
                }
            } catch (\Exception $e) {
                $response->paging->total = 0;
            }

            $response->results = $assets;
            return $response;

        } else {
            return $assets;
        }
    }


    /**
     * Gets multiple assets given Quicklink Id
     * @param String $quicklinkId
     * @param Bool $acl - True to include acl hash on asset object
     * @param Array $searchParams - Array of search parameters
     * @param Bool $wrapPagination
     * @return Array(Asset)
     */
    public function getAssetsByQuicklinkId($quicklinkId, $acl = false, $searchParams = array(), $wrapPagination = false) {
        $assets = array();
        $searchQuery = '?';

        foreach ($searchParams as $key => $value) {
            $searchQuery .= $key . '=' . $value . '&';
        }

        $searchQuery = substr($searchQuery, 0, -1);
        $clientResponse = $this->webClient->get(sprintf(MediaSiloResourcePaths::QUICKLINK_ASSETS, $quicklinkId) . $searchQuery);
        $assetResults = json_decode($clientResponse->getBody());

        if(!empty($assetResults)) {
            foreach($assetResults as $assetResult) {
                $asset = Asset::fromStdClass($assetResult);

                if ($acl) {
                    $acl = Asset::convertAssetPermissionsArrayToAcl($assetResult->permissions);
                    $asset->setAcl($acl);
                }

                array_push($assets, $asset);
            }
        }

        if ($wrapPagination) {
            $response = new stdClass();
            $response->paging = new stdClass();

            try {
                foreach($clientResponse->getHeaders() as $header) {
                    $parts = explode(":", $header);
                    if ($parts[0] == 'total-results') {
                        $response->paging->total = intval($parts[1]);
                    }
                }
            } catch (\Exception $e) {
                $response->paging->total = 0;
            }

            $response->results = $assets;
            return $response;

        } else {
            return $assets;
        }
    }


    private function getRoleManager() {
        if (isset($this->roleManager)) {
            return $this->roleManager;
        } else {
            $this->roleManager = new RoleManager($this->webClient);
            return $this->roleManager;
        }
    }

    private function attachAclToAsset(&$asset) {
        $role = $this->getRoleManager()->getUserRoleForAsset($asset);
        $asset->acl = $role->getPermissionGroups();
    }

}
