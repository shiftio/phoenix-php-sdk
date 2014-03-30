<?php

namespace mediasilo\quicklink\analytics;

use mediasilo\quicklink\QuickLink;
use mediasilo\quicklink\Configuration;
use mediasilo\share\Share;

class AnalyzedQuickLink extends QuickLink {

	private $totalEvents;
	private $events;

	public function __construct($assetIds, Configuration $configuration, $description, array $shares, $title) {
        $this->assetIds = $assetIds;
        $this->configuration = $configuration;
        $this->description = $description;
        $this->shares = $shares;
        $this->title = $title;
        $this->totalEvents = 0;
        $this->events = array();
	}

    public static function fromJson($json) {
        $mixed = json_decode($json);
        return AnalyzedQuickLink::fromStdClass($mixed);
    }

    public static function fromStdClass($stdClass) {
        $configuration = Configuration::fromStdClass($stdClass->configuration);

        $shares = array();
        if(isset($stdClass->shares) && $stdClass->shares != null) {
            foreach($stdClass->shares as $share) {
                array_push($shares, Share::fromStdClass($share));
            }
        }

        $quickLink = new AnalyzedQuickLink($stdClass->assetIds, $configuration, $stdClass->description, $shares, $stdClass->title);
        $quickLink->id = isset($stdClass->id) ? $stdClass->id : null;
        $quickLink->title = isset($stdClass->title) ? $stdClass->title : null;
        $quickLink->description = isset($stdClass->description) ? $stdClass->description : null;
        $quickLink->ownerId = isset($stdClass->ownerId) ? $stdClass->ownerId : null;
        $quickLink->modified = isset($stdClass->modified) ? $stdClass->modified : null;
        $quickLink->created = isset($stdClass->created) ? $stdClass->created : null;

        return $quickLink;
    }

    public function setEvents($events)
    {
        $this->events = $events;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function setTotalEvents($totalEvents)
    {
        $this->totalEvents = $totalEvents;
    }

    public function getTotalEvents()
    {
        return $this->totalEvents;
    }

    public function addEvent($analyticsEvent) {
        if(!isset($this->events)) {
            $this->events = array();
        }

        array_push($this->events, $analyticsEvent);
    }


}