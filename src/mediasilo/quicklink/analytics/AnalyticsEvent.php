<?php

namespace mediasilo\quicklink\analytics;

class AnalyticsEvent {
	private $eventName;
	private $totalCount;
	private $totalUniqueVisitors;

    function __construct($eventName, $totalCount, $totalUniqueVisitors)
    {
        $this->eventName = $eventName;
        $this->totalCount = $totalCount;
        $this->totalUniqueVisitors = $totalUniqueVisitors;
    }

    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }

    public function getEventName()
    {
        return $this->eventName;
    }

    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
    }

    public function getTotalCount()
    {
        return $this->totalCount;
    }

    public function setTotalUniqueVisitors($totalUniqueVisitors)
    {
        $this->totalUniqueVisitors = $totalUniqueVisitors;
    }

    public function getTotalUniqueVisitors()
    {
        return $this->totalUniqueVisitors;
    }




}