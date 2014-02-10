<?php

namespace mediasilo\http;

use mediasilo\http\exception\RateLimitException;
use mediasilo\http\exception\ValidationException;
use mediasilo\http\exception\NotFoundException;

class HttpResponseHandler {
    public function handle($response, $responseCode) {
        if(!$responseCode >= 200 && $responseCode <= 206) {

        }

        if($responseCode == 429) {
            throw new RateLimitException("Your API rate limit has been exceeded", json_decode($response));
        }

        if($responseCode == 400) {
            throw new ValidationException("The request was invalid. Review the error collection to see what the problem was.", json_decode($response));
        }

        if($responseCode == 404) {
            throw new NotFoundException("There was no resource matching the request.", json_decode($response));
        }
    }

}