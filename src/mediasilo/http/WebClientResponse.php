<?php

namespace mediasilo\http;

class WebClientResponse {

    private $headers;
    private $body;

    function __construct($body, $headers)
    {
        $this->body = $body;
        $this->headers = $headers;
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
}
