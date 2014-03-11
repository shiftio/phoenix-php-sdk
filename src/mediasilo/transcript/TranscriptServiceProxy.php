<?php

namespace mediasilo\transcript;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\transcript\TranscriptService;

class TranscriptServiceProxy {

    private $webClient;

    public function __construct(WebClient $webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Get all transcript services available for this user
     * @return array(TranscriptService)
     */
    public function getTranscriptServices() {
        $transcriptServices = array();
        
        $transcriptResults = json_decode($this->webClient->get(MediaSiloResourcePaths::TRANSCRIPT_SERVICES));

        if(!empty($transcriptResults)) {
            foreach($transcriptResults as $transcriptService) {
                $transcriptService = TranscriptService::fromStdClass($transcriptService);
                array_push($transcriptServices, $transcriptService);
            }
        }

        return $transcriptServices;
    }

}