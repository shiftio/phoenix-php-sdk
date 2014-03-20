<?php

namespace mediasilo\channel;

use mediasilo\model\Serializable;

class Channel implements Serializable {

    public $id;
    public $name;
    public $dateCreated;
    public $autoPlay;
    public $height;
    public $width;
    public $playback;
    public $public;
    public $stretching;
    public $feeds;
    public $assets;

    function __construct($id, $name, $dateCreated, $autoPlay, $height, $width, $playback, $public, $stretching, $feeds, array $assets)
    {
        $this->id = $id;
        $this->name = $name;
        $this->dateCreated = $dateCreated;
        $this->autoPlay = $autoPlay;
        $this->height = $height;
        $this->width = $width;
        $this->playback = $playback;
        $this->public = $public;
        $this->stretching = $stretching;
        $this->feeds = $feeds;
        $this->assets = $assets;
    }

    function toJson() {
        return json_encode($this);
    }

    public static function fromJson($json) {
    	$mixed = json_decode($json);
        return new Channel($mixed->id, $mixed->name, $mixed->dateCreated, $mixed->autoPlay, $mixed->height, $mixed->width, $mixed->playback, $mixed->public, $mixed->stretching, $mixed->feeds, $mixed->assets);
    }

    public static function fromStdClass($stdClass) {
        return new Channel($stdClass->id, $stdClass->name, $stdClass->dateCreated, $stdClass->autoPlay, $stdClass->height, $stdClass->width, $stdClass->playback, $stdClass->public, $stdClass->stretching, $stdClass->feeds, $stdClass->assets);
    }

}