<?php

namespace mediasilo\project;

use mediasilo\model\Serializable;

class Project implements Serializable{

    public $id;

    public $name;

    public $description;

    public $favorite;

    function __construct($description, $id = null, $favorite, $name)
    {
        $this->description = $description;
        $this->id = $id;
        $this->favorite = $favorite;
        $this->name = $name;
    }

    function toJson() {
        return json_encode($this);
    }

    public static function fromJson($json) {
        $mixed = json_decode($json);
        return new Project($mixed->description, $mixed->id, boolval($mixed->favorite), $mixed->name);
    }
}