<?php

namespace mediasilo\quicklink;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\project\Project;

class QuickLinkProxy {

    private $webClient;

    public function __construct(WebClient $webClient) {
        $this->webClient = $webClient;
    }

    /**
     * @param QuickLink $quickLink
     */
    public function createQuickLink(QuickLink $quickLink)
    {
        $result = json_decode($this->webClient->post(MediaSiloResourcePaths::QUICKLINK, $quickLink->toJson()));
        $quickLink->id = $result->id;

        var_dump($quickLink);
    }

    public function getQuickLink($id) {
        $result = $this->webClient->get(MediaSiloResourcePaths::QUICKLINK."/".$id);
        return QuickLink::fromJson($result);
    }
}