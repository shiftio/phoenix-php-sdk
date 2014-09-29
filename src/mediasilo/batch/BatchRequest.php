<?php

namespace mediasilo\batch;

use mediasilo\model\Serializable;

class BatchRequest implements Serializable {

    public $httpMethod = "";
    public $resourcePath = "";
    public $payload = null;

    public static $validHttpMethods = Array('GET', 'POST', 'PUT', 'DELETE');

    function __construct($httpMethod, $resourcePath, $payload = null) {
        $this->httpMethod = $httpMethod;
        $this->resourcePath = $resourcePath;
        if (!is_null($payload)) {
            $this->payload = $payload;
        }
    }

    function isValid() {
        return in_array(strtoupper($this->httpMethod), BatchRequest::$validHttpMethods) && !empty($this->resourcePath);
    }


    function toJson()
    {
        return json_encode($this);
    }


    public static function fromJson($json)
    {
        $mixed = json_decode($json);
        if (!isset($mixed->payload)) {
            $mixed->payload = null;
        }
        return new BatchRequest($mixed->httpMethod, $mixed->resourcePath, $mixed->payload);
    }

}
