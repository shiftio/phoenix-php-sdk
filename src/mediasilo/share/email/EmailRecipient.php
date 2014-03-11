<?php

namespace mediasilo\share\email;

class EmailRecipient {
    public $userId;
    public $emailAddress;

    function __construct($emailAddress, $userId)
    {
        $this->emailAddress = $emailAddress;
        $this->userId = $userId;
    }


}