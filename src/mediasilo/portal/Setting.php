<?php

namespace mediasilo\portal;

use mediasilo\model\Serializable;

class Setting implements Serializable {

    public $key;
    public $value;

    function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function toJson()
    {
        $result = new \stdClass();
        $result->key = $this->key;
        $result->value = $this->value;
        return json_encode($result);
    }

    public static function fromJson($json)
    {
        $mixed = json_decode($json);
        return Setting::fromStdClass($mixed);
    }

    private static function fromStdClass($stdClass)
    {
        if($stdClass != null) {
            $key = isset($stdClass->key) ? $stdClass->key : null;
            $value = isset($stdClass->value) ? $stdClass->value : null;
            return new Setting($key, $value);
        }
        return null;
    }
}
