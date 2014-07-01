<?php

namespace mediasilo\portal;

use mediasilo\model\Serializable;

class Channel implements Serializable {

    public $title;
    public $description;
    public $slug;
    public $type;
    public $targetObjectId;

    function __construct($description, $slug, $targetObjectId, $title, $type)
    {
        $this->description = $description;
        $this->slug = $slug;
        $this->targetObjectId = $targetObjectId;
        $this->title = $title;
        $this->type = $type;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setTargetObjectId($targetObjectId)
    {
        $this->targetObjectId = $targetObjectId;
    }

    public function getTargetObjectId()
    {
        return $this->targetObjectId;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function toJson()
    {
        $result = new \stdClass();
        $result->title = $this->title;
        $result->description = $this->description;
        $result->slug = $this->slug;
        $result->type = $this->type;
        $result->targetObjectId = $this->targetObjectId;
        return json_encode($result);
    }

    public static function fromJson($json)
    {
        $mixed = json_decode($json);
        return Channel::fromStdClass($mixed);
    }

    public static function fromStdClass($stdClass)
    {
        if($stdClass != null) {
            $title = isset($stdClass->title) ? $stdClass->title : null;
            $description = isset($stdClass->description) ? $stdClass->description : null;
            $slug = isset($stdClass->slug) ? $stdClass->slug : null;
            $type = isset($stdClass->type) ? $stdClass->type : null;
            $targetObjectId = isset($stdClass->targetObjectId) ? $stdClass->targetObjectId : null;
            return new Channel($description, $slug, $targetObjectId, $title, $type);
        }
        return null;
    }
}
