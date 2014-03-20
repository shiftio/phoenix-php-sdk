<?php

namespace mediasilo\transcript;

use mediasilo\model\Serializable;

class Transcript implements Serializable {

    public $formats;
    public $logs;

    function __construct($formats, array $logs) {
        $this->formats = $formats;
        $this->logs = $logs;
    }

    function toJson() {
        return json_encode($this);
    }

    public static function fromJson($json) {
    	$mixed = json_decode($json);
        return new Transcript($mixed->formats, $mixed->logs);
    }

    public static function fromStdClass($stdClass) {
        return new Transcript($stdClass->formats, $stdClass->logs);
    }

}