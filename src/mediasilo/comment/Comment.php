<?php

namespace mediasilo\comment;

use mediasilo\model\Serializable;

class Comment implements Serializable{

    public $id;
    public $at;
    public $inResponseTo;
    public $context;
    public $startTimeCode;
    public $endTimeCode;
    public $dateCreated;
    public $body;
    public $user;
    public $responses;

    function __construct($at, $inResponseTo, $context, $body)
    {
        $this->at = $at;
        $this->inResponseTo = $inResponseTo;
        $this->context = $context;
        $this->body = $body;
    }

    function toJson() {
        return json_encode($this);
    }

    public static function fromJson($json) {
        $mixed = json_decode($json);

        if(isset($mixed->results)) {
            $commentArray = array();
            foreach($mixed->results as $aComment) {
                $comment = new Comment($aComment->at, $aComment->inResponseTo, $aComment->context, $aComment->body);
                $comment->id = $aComment->id;
                $comment->startTimeCode = $aComment->startTimeCode;
                $comment->endTimeCode = $aComment->endTimeCode;
                $comment->dateCreated = $aComment->dateCreated;
                $comment->user = $aComment->user;
                $comment->responses = $aComment->responses;

                array_push($commentArray, $comment);
            }

            return $commentArray;
        }
        else {
            $comment = new Comment($mixed->at, $mixed->inResponseTo, $mixed->context, $mixed->body);
            $comment->id = $mixed->id;
            $comment->startTimeCode = $mixed->startTimeCode;
            $comment->endTimeCode = $mixed->endTimeCode;
            $comment->dateCreated = $mixed->dateCreated;
            $comment->user = $mixed->user;
            $comment->responses = $mixed->responses;

            return $comment;
        }
    }

}