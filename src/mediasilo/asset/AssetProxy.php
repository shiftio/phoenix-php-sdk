<?php

namespace mediasilo\asset;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\asset\Asset;

class AssetProxy {

    private $webClient;

    public function __construct(WebClient $webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Gets an exiting asset given an asset Id
     * @param $id
     * @return Asset
     */
    public function getAsset($id, $acl = false) {
        $asset = Asset::fromJson($this->webClient->get(MediaSiloResourcePaths::ASSETS . "/" . $id));
        if($acl == true) {

        }

        return $asset;
    }

}