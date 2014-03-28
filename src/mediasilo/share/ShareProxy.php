<?php

namespace mediasilo\share;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;

class ShareProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Create
     * @param Share $share
     */
    public function createShare(Share $share)
    {
        $result = json_decode($this->webClient->post(MediaSiloResourcePaths::SHARE, $share->toJson()));
        $share->id = $result->id;
    }

    /**
    * Get
    * @param string $targetObjectId the identifier of the object that was shared
    */
    public function getShares($targetObjectId) {
        $foo = $this->webClient->get(MediaSiloResourcePaths::SHARE."?targetObjectId=".$targetObjectId);

        $result = json_decode($this->webClient->get(MediaSiloResourcePaths::SHARE."?targetObjectId=".$targetObjectId));

        return $result;
    }
}