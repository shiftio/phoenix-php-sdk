<?php

namespace mediasilo\transcript;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\transcript\Transcript;

class TranscriptProxy {

    private $webClient;

    public function __construct(WebClient $webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Gets an exiting asset given an asset Id
     * @param $id
     * @return Asset
     */
    public function getTranscript($assetId) {
        return Transcript::fromJson($this->webClient->get(MediaSiloResourcePaths::TRANSCRIPTS . "/" . $assetId));
    }

}