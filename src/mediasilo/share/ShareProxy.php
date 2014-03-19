<?php

namespace mediasilo\quicklink;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\project\Project;

class ShareProxy {

    private $webClient;

    public function __construct(WebClient $webClient) {
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
}