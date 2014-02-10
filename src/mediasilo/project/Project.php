<?php

namespace mediasilo\project;

use mediasilo\model\Serializable;

class Project implements Serializable{

    public $id;

    public $name;

    public $description;

    public $isFavorite;

    function __construct($description, $id = null, $isFavorite, $name)
    {
        $this->description = $description;
        $this->id = $id;
        $this->isFavorite = $isFavorite;
        $this->name = $name;
    }

    function toJson() {
        return json_encode($this);
    }

    public static function fromJson($json) {
        $mixed = json_decode($json);
        return new Project($mixed->description, $mixed->id, boolval($mixed->isFavorite), $mixed->name);
    }
}