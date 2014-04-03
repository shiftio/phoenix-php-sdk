<?php

namespace mediasilo\transcript;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\transcript\Transcript;

class TranscriptProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Gets an exiting asset given an asset Id
     * @param $assetId
     * @return Transcript
     */
    public function getTranscript($assetId) {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::TRANSCRIPTS . "/" . $assetId);
        return Transcript::fromJson($clientResponse->getBody());
    }

}
