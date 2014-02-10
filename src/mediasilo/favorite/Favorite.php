<?php

namespace mediasilo\favorite;

use mediasilo\model\Serializable;

class Favorite implements Serializable{

    public  $id;

    public $ownerId;

    public $targetObjectId;

    public $context;

    public $dateCreated;

    function __construct($context, $dateCreated, $id, $ownerId, $targetObjectId)
    {
        $this->context = $context;
        $this->dateCreated = $dateCreated;
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->targetObjectId = $targetObjectId;
    }

    function toJson() {
        return json_encode($this);
    }

    public static function fromJson($json) {
        return new Favorite($json->context, $json->dateCreated, $json->id, $json->ownerId, $json->targetObjectId);
    }
}