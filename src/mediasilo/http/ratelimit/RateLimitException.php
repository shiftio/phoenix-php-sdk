<?php

namespace mediasilo\http\ratelimit;

use Exception;
use mediasilo\http\ratelimit\RateLimt;

class RateLimitException extends Exception {

    private $rateLimit;

    public function __construct($message, $rateLimit) {
        parent::__construct($message);
        $this->rateLimit = new RateLimit($rateLimit);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->rateLimit}]: {$this->message}\n";
    }

    public function getRateLimit() {
        return $this->rateLimit;
    }

}