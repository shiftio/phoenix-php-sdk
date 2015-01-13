<?php

namespace mediasilo\http;

use Exception;
use stdClass;

class WebClientResponse {

    private $code;
    private $headers;
    private $body;

    function __construct($body, $headers, $code = null)
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->code = $code;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param null $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $queryResults
     * @return stdClass
     */
    public function buildPaginatedResponse($queryResults) {
        $response = new stdClass();
        $response->paging = new stdClass();

        try {
            foreach($this->getHeaders() as $header) {
                $parts = explode(":", $header);
                if ($parts[0] == 'total-results') {
                    $response->paging->total = intval($parts[1]);
                }
            }

            if (!isset($response->paging->total)) {
                $headers = $this->getHeaders();
                $response->paging->total = intval($headers['total-results']);
            }

        } catch (Exception $e) {
            $response->paging->total = 0;
        }

        $response->results = $queryResults;
        return $response;
    }

}
