<?php

namespace mediasilo\batch;


use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\http\exception\ValidationException;
use stdClass;

class BatchProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    public function processRequests($requests) {

        $requestsToSend = Array();

        foreach($requests as $request) {

            // If a batch request object was passed in, validate and push it on the stack
            if (is_a($request, 'BatchRequest')) {
                if ($request->isValid()) {
                    array_push($requestsToSend, $request);
                } else {
                    throw new ValidationException('Invalid Batch Request Object', null);
                }
            }

            // If an array was passed in, try to build a BatchRequest Object from it.
            else if (is_array($request)) {
                array_push($requestsToSend, $this->parseArrayRequest($request));
            }

            // If any other object type is passed in, try to parse it.
            else if (is_object($request)) {
                array_push($requestsToSend, $this->parseObjectRequest($request));
            }

            else {
                throw new ValidationException('Invalid Batch Request Object', null);
            }
        }

        $result = json_decode($this->webClient->post(MediaSiloResourcePaths::BATCH, json_encode($requestsToSend)));
        return $result;
    }


    private function parseArrayRequest($request) {
        if (!array_key_exists('httpMethod', $request)) {
            throw new ValidationException('All requests must have a request method.', null);
        }
        else if (!in_array(strtoupper($request['httpMethod']), BatchRequest::$validHttpMethods)) {
            throw new ValidationException(sprintf('Invalid http method "%s".', $request['httpMethod']), null);
        }
        else if (!array_key_exists('resourcePath', $request)) {
            throw new ValidationException('All requests must have a request path', null);
        }

        if (array_key_exists('payload', $request)) {
            $payload = $request['payload'];
        } else {
            $payload = null;
        }
        return new BatchRequest(strtoupper($request['httpMethod']), $request['resourcePath'], $payload);
    }


    private function parseObjectRequest($request) {
        if (!property_exists($request, 'httpMethod')) {
            throw new ValidationException('All requests must have a request method.', null);
        }
        else if (!in_array(strtoupper($request->httpMethod), BatchRequest::$validHttpMethods)) {
            throw new ValidationException(sprintf('Invalid http method "%s".', $request['httpMethod']), null);
        }
        else if (!property_exists($request, 'resourcePath')) {
            throw new ValidationException('All requests must have a request path', null);
        }
        else {
            if (property_exists($request, 'payload')) {
                $payload = $request->payload;
            } else {
                $payload = null;
            }
            return new BatchRequest(strtoupper($request->httpMethod), $request->resourcePath, $payload);
        }
    }

}
