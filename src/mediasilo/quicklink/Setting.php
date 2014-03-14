<?php

namespace mediasilo\quicklink;

class Setting {
    public $key;
    public $value;

    function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}