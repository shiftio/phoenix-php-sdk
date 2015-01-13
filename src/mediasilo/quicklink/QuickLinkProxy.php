<?php

namespace mediasilo\quicklink;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\quicklink\analytics\AnalyticsEvent;
use mediasilo\quicklink\analytics\AnalyzedQuickLink;
use mediasilo\quicklink\analytics\QuickLinkAnalyticsProxy;

class QuickLinkProxy {

    private $webClient;
    private $quicklinkAnalyticsProxy;

    public function __construct(WebClient $webClient) {
        $this->webClient = $webClient;
        $this->quicklinkAnalyticsProxy = new QuickLinkAnalyticsProxy($webClient);
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
     * @param bool $includeAnalytics if you want to append analytics data to each quicklink result
     * @returns Quicklink
     */
    public function getQuickLink($id, $includeAnalytics = false) {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::QUICKLINK."/".$id);
        $result = json_decode($clientResponse->getBody());

        $quicklink = null;
        if($includeAnalytics) {
            $quicklinkAnalytics = $this->getQuicklinkAnalytics(array($id));
            $quicklinkAnalyticsEvents = $this->getQuicklinkAnalyticsEvents($result->id, $quicklinkAnalytics);
            $quicklink = $this->makeAnalyzedQuicklink($quicklinkAnalyticsEvents, $result);

        }
        else {
            $quicklink =  QuickLink::fromStdClass($result);
        }

        return $quicklink;
    }

    /**
     * Read Many
     * @param String - Additional query parameters to include
     * @param Bool - if you want to append analytics data to each quicklink result
     * @param Bool - true to wrap the response with pagination data
     * @returns Array[QuickLink]
     */
    public function getQuicklinks($params = null, $includeAnalytics = false, $wrapPagination = false) {
        if (is_null($params)) {
            return $this->getQuicklinksWith("", $includeAnalytics, $wrapPagination);
        } else {
            return $this->getQuicklinksWith($params, $includeAnalytics, $wrapPagination);
        }
    }

    /**
     * Read Many
     * @param String - Additional query parameters to include
     * @param Bool - if you want to append analytics data to each quicklink result
     * @param Bool - true to wrap the response with pagination data
     * @returns Array[QuickLink]
     */
    private function getQuicklinksWith($params, $includeAnalytics = false, $wrapPagination = false) {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::QUICKLINK."?".$params);
        $results = json_decode($clientResponse->getBody());

        $quicklinks = array();

        if($includeAnalytics) {
            $quicklinkAnalyticsEvents = $this->getQuicklinkAnalytics($this->getIdsFromQuicklinks($results));

            foreach($results as $result) {
                $quicklinkEventBucket = $this->getQuicklinkAnalyticsEvents($result->id, $quicklinkAnalyticsEvents);
                array_push($quicklinks, $this->makeAnalyzedQuicklink($quicklinkEventBucket, $result));
            }

        }
        else {
            foreach($results as $result) {
                array_push($quicklinks, QuickLink::fromStdClass($result));
            }
        }

        return $wrapPagination ? $clientResponse->buildPaginatedResponse($quicklinks) : $quicklinks;
    }

    /**
     * Update
     * @param QuickLink $quickLink
     */
    public function updateQuicklink(QuickLink $quickLink) {
        $this->webClient->put(MediaSiloResourcePaths::QUICKLINK, $quickLink->toJson());
    }

    /**
     * Read analytics buckets for a quicklink
     * @param $quicklinkIds
     * @return mixed
     */
    private function getQuicklinkAnalytics($quicklinkIds) {
        $result = $this->quicklinkAnalyticsProxy->getQuicklinkAggregateEvents($quicklinkIds);
        return $result->aggregations->quicklinks->buckets;
    }

    /**
     * Find analytics event for a given quicklink id
     * @param $quicklinkId
     * @param $events
     * @return mixed
     */
    private function getQuicklinkAnalyticsEvents($quicklinkId, $events) {
        foreach($events as $event) {
            if($quicklinkId == $event->key) {
                return $event;
            }
        }
    }

    /**
     * Gets an array of quicklink ids from an array of quicklinks
     * @param $quicklinks
     * @return array
     */
    private function getIdsFromQuicklinks($quicklinks) {
        $quicklinksIds = array();
        foreach($quicklinks as $quicklink) {
            array_push($quicklinksIds, $quicklink->id);
        }

        return $quicklinksIds;
    }

    /**
     * Build an AnalyzedQuicklink by combining the quicklink and analytics data
     * @param $eventBuckets
     * @param $quicklinkResult
     * @return AnalyzedQuickLink
     */
    private function makeAnalyzedQuicklink($eventBuckets, $quicklinkResult) {
        $quicklink = AnalyzedQuickLink::fromStdClass($quicklinkResult);

        if(isset($eventBuckets)) {
            $quicklink->setTotalEvents($eventBuckets->doc_count);
            foreach($eventBuckets->events->buckets as $event) {
                $analyticsEvent = new AnalyticsEvent($event->key, $event->doc_count, sizeof($event->unique_visitors->buckets));
                $quicklink->addEvent($analyticsEvent);
            }
        }

        return $quicklink;
    }
}