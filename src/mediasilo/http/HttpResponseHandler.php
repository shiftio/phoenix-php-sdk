<?php

namespace mediasilo\http;

use mediasilo\http\exception\NotAuthenticatedException;
use mediasilo\http\exception\RateLimitException;
use mediasilo\http\exception\ValidationException;
use mediasilo\http\exception\NotFoundException;
use mediasilo\http\exception\ConnectionException;
use mediasilo\http\exception\NotAuthorizedException;

class HttpResponseHandler {
    public function handle($response, $responseCode) {
        if($responseCode >= 200 && $responseCode <= 206) {
            //success
        }
        if($responseCode == 429) {
            throw new RateLimitException("Your API rate limit has been exceeded", json_decode($response));
        }
        if($responseCode == 400) {
            throw new ValidationException("The request was invalid. Review the error collection to see what the problem was.", json_decode($response));
        }
        if($responseCode == 401) {
            throw new NotAuthenticatedException("You are not authenticated, which is required to perform this request", json_decode($response));
        }
        if($responseCode == 403) {
            throw new NotAuthorizedException("You are not authorized to perform this request", json_decode($response));
        }
        if($responseCode == 404) {
            throw new NotFoundException("There was no resource matching the request.", json_decode($response));
        }
        if($responseCode == 0 && $response == false) {
            throw new ConnectionException("There was a problem connecting to the MediaSilo API", json_decode($response));
        }
        if ($responseCode == 303) {
          throw new ConnectionException("This account has been migrated", json_decode($response));
        }
    }
}