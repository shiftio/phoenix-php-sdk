<?php

namespace mediasilo\model;

interface Serializable {
    public function toJson();
    public static function fromJson($json);
}