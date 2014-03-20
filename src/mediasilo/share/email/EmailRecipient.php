<?php

namespace mediasilo\share\email;

class EmailRecipient {
    public $userId;
    public $email;

    function __construct($email, $userId)
    {
        $this->email = $email;
        $this->userId = $userId;
    }


}