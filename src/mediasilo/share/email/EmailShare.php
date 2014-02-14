<?php

namespace mediasilo\share\email;

use mediasilo\model\Serializable;

class EmailShare implements Serializable {
    public $audience;
    public $subject;
    public $message;

    function __construct(array $audience, $message, $subject)
    {
        $this->audience = $audience;
        $this->message = $message;
        $this->subject = $subject;
    }


    public function toJson()
    {
        // TODO: Implement toJson() method.
    }

    public static function fromJson($json)
    {
        // TODO: Implement fromJson() method.
    }

    public static function fromStdClass($stdClass)
    {
        if($stdClass != null) {
            $audience = isset($stdClass->audience) ? $stdClass->audience : null;
            $message = isset($stdClass->message) ? $stdClass->message : null;
            $subject = isset($stdClass->subject) ? $stdClass->subject : null;

            return new EmailShare($audience, $message, $subject);
        }
    }
}