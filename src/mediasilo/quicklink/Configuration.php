<?php

namespace mediasilo\quicklink;

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
        // TODO: Implement toJson() method.
    }

    public static function fromJson($json)
    {
        // TODO: Implement fromJson() method.
    }

    public static function fromStdClass($stdClass)
    {
        if($stdClass != null) {
            $id = isset($stdClass->id) ? $stdClass->id : null;
            $settings = isset($stdClass->settings) ? $stdClass->settings : null;
            return new Configuration($id, $settings);
        }

        return null;
    }
}