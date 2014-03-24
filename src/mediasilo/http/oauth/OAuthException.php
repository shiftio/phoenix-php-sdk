<?php

namespace mediasilo\http\oauth;

use Exception;

class OAuthException extends Exception {

    public function __construct($message, $errors = null) {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->errors}]: {$this->message}\n";
    }

    public function getErrors() {
        return $this->errors;
    }

}
