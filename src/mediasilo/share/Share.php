<?php

namespace mediasilo\share;

use mediasilo\model\Serializable;
use mediasilo\share\email\EmailShare;

class Share implements Serializable {
    public $id;
    public $targetObjectId;
    public $emailShare;
    public $created;
    public $sharedBy;

    function __construct($emailShare, $id, $targetObjectId)
    {
        $this->emailShare = $emailShare;
        $this->id = $id;
        $this->targetObjectId = $targetObjectId;
    }


    public function toJson()
    {
        return json_encode($this);
    }

    public static function fromJson($json)
    {
        // TODO: Implement fromJson() method.
    }

    public static function fromStdClass($stdClass)
    {
        if($stdClass != null) {
            $emailShare = EmailShare::fromStdClass($stdClass->emailShare);

            $id = isset($stdClass->id) ? $stdClass->id : null;
            $targetObjectId = isset($stdClass->targetObjectId) ? $stdClass->targetObjectId : null;
            $share = new Share($emailShare, $id, $targetObjectId);
            $share->created = isset($stdClass->created) ? $stdClass->created : null;
            $share->sharedBy = isset($stdClass->sharedBy) ? $stdClass->sharedBy : null;

            return $share;
        }

        return null;
    }
}