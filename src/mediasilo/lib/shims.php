<?php

// Shim for boolval in PHP v5.5
if( !function_exists('boolval')) {
    function boolval($val) {
        return (bool) $val;
    }
}

