<?php

namespace mediasilo\quicklink;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\project\Project;

class QuickLinkProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Create
     * @param QuickLink $quickLink
     */
    public function createQuickLink(QuickLink $quickLink)
    {
        $result = json_decode($this->webClient->post(MediaSiloResourcePaths::QUICKLINK, $quickLink->toJson()));
        $quickLink->id = $result->id;
    }

    /**
     * Read One
     * @param String $id
     * @returns Quicklink
     */
    public function getQuickLink($id) {
        $result = $this->webClient->get(MediaSiloResourcePaths::QUICKLINK."/".$id);
        return QuickLink::fromJson($result);
    }

    /**
     * Read Many
     * @returns String
     */
    public function getQuicklinks() {
        $result = $this->webClient->get(MediaSiloResourcePaths::QUICKLINK);
        return json_decode($result);
    }

    /**
     * Update
     * @param QuickLink $quickLink
     */
    public function updateQuicklink(QuickLink $quickLink) {
        $this->webClient->put(MediaSiloResourcePaths::QUICKLINK, $quickLink->toJson());
    }
}