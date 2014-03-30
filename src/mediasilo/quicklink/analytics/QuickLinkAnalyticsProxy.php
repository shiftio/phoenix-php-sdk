<?php

namespace mediasilo\quicklink\analytics;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\quicklink\analytics\query\QuicklinksAggregateEvents;

class QuickLinkAnalyticsProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    public function getQuicklinkAggregateEvents($quickLinkIds) {
    	$queryBuilder = new QuicklinksAggregateEvents($quickLinkIds);
    	$query = $queryBuilder->getQuery();

        $result = $this->webClient->post(MediaSiloResourcePaths::ANALYTICS, $query);
        return json_decode($result);    
    }

}