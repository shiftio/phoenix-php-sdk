<?php

namespace mediasilo\http;

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
}
