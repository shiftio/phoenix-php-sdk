<?php

namespace mediasilo\portal;

use mediasilo\model\Serializable;

class Configuration implements Serializable {
    public $id;
    public $settings;

    function __construct($id, $settings)
    {
        $this->id = $id;
        $this->settings = $settings;
    }

    public function toJson()
    {
        $result = new \stdClass();
        $result->id = $this->id;
        $result->settings = $this->settings;
        return json_encode($result);
    }

    public static function fromJson($json)
    {
        $mixed = json_decode($json);
        return Configuration::fromStdClass($mixed);
    }

    private static function fromStdClass($stdClass)
    {
        if($stdClass != null) {
            $id = isset($stdClass->id) ? $stdClass->id : null;
            $settings = isset($stdClass->settings) ? $stdClass->settings : null;
            return new Configuration($id, $settings);
        }

        return null;
    }
}
