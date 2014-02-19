<?php

namespace mediasilo\asset;

use mediasilo\model\Serializable;

class Asset implements Serializable {

    public $id;
    public $title;
    public $description;
    public $filename;
    public $dateCreated;
    public $projectId;
    public $acl;

    function __construct($id, $title, $description, $filename, $dateCreated, $projectId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->filename = $filename;
        $this->dateCreated = $dateCreated;
        $this->projectId = $projectId;
    }

    function toJson() {
        return json_encode($this);
    }

    public static function fromJson($json) {
    	$mixed = json_decode($json);
        return new Asset($mixed->id, $mixed->title, $mixed->description, $mixed->filename, $mixed->dateCreated, $mixed->projectId);
    }

    public static function fromStdClass($stdClass) {
        return new Asset($stdClass->id, $stdClass->title, $stdClass->description, $stdClass->filename, $stdClass->dateCreated, $stdClass->projectId);
    }

}