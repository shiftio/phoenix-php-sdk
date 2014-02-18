<?php

namespace mediasilo\asset;

use mediasilo\model\Serializable;

class Asset implements Serializable {

    public $id;
    public $title;
    public $description;
    public $filename;
    public $dateCreated;
    public $acl;

    function __construct($id, $title, $description, $filename, $dateCreated)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->filename = $filename;
        $this->dateCreated = $dateCreated;
    }

    function toJson() {
        return json_encode($this);
    }

    public static function fromJson($json) {
    	$mixed = json_decode($json);
        return new Asset($mixed->id, $mixed->title, $mixed->description, $mixed->filename, $mixed->dateCreated);
    }

}