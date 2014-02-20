<?php

namespace mediasilo\transcript;

use mediasilo\model\Serializable;

class TranscriptService implements Serializable {

    public $id;
    public $name;
    public $billOnDemand;
    public $costPerClip;
    public $costPerMinute;
    public $turnaroundHours;

    function __construct($id, $name, $billOnDemand, $costPerClip, $costPerMinute, $turnaroundHours) {
        $this->id = $id;
        $this->name = $name;
        $this->billOnDemand = $billOnDemand;
        $this->costPerClip = $costPerClip;
        $this->costPerMinute = $costPerMinute;
        $this->turnaroundHours = $turnaroundHours;
    }

    function toJson() {
        return json_encode($this);
    }

    public static function fromJson($json) {
    	$mixed = json_decode($json);
        return new TranscriptService($mixed->id, $mixed->name, $mixed->billOnDemand, $mixed->costPerClip, $mixed->costPerMinute, $mixed->turnaroundHours);
    }

    public static function fromStdClass($stdClass) {
        return new TranscriptService($stdClass->id, $stdClass->name, $stdClass->billOnDemand, $stdClass->costPerClip, $stdClass->costPerMinute, $stdClass->turnaroundHours);
    }

}