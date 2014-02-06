<?php

namespace mediasilo\http\ratelimit;

class RateLimit {

    private $userId;
    private $limit;
    private $remaining;
    private $reset;

    public function __construct($rateLimitObject) {
        $this->userId = $rateLimitObject->userId;
        $this->limit = $rateLimitObject->limit;
        $this->remaining = $rateLimitObject->remaining;
        $this->reset = $rateLimitObject->reset;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getRemaining()
    {
        return $this->remaining;
    }

    public function getReset()
    {
        return $this->reset;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    function __toString()
    {
        return __CLASS__ . sprintf("userId:%s, limit:%s, remaining:%s, reset:%s", $this->userId, $this->limit, $this->remaining, $this->reset);
    }


}