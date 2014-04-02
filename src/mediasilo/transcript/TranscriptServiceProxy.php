<?php

namespace mediasilo\transcript;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\transcript\TranscriptService;

class TranscriptServiceProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Get all transcript services available for this user
     * @return array(TranscriptService)
     */
    public function getTranscriptServices() {
        $transcriptServices = array();

        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::TRANSCRIPT_SERVICES);
        $transcriptResults = json_decode($clientResponse->getBody());

        if(!empty($transcriptResults)) {
            foreach($transcriptResults as $transcriptService) {
                $transcriptService = TranscriptService::fromStdClass($transcriptService);
                array_push($transcriptServices, $transcriptService);
            }
        }

        return $transcriptServices;
    }

}
