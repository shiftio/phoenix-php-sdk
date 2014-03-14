<?php

namespace mediasilo\me;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\me\Me;

class MeProxy
{

    private $webClient;

    public function __construct(WebClient $webClient)
    {
        $this->webClient = $webClient;
    }

    /**
     * Gets the currently logged in user
     * @return Me
     */
    public function getMe()
    {
        $me = Me::fromJson($this->webClient->get(MediaSiloResourcePaths::ME));

        return $me;
    }
}
