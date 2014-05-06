<?php

namespace mediasilo\http\exception;

use Exception;

class NotAuthenticatedException extends Exception {

    public function __construct($message, $errors) {
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
