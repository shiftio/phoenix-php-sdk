<?php

namespace mediasilo\comment;

use mediasilo\model\Serializable;

class CommentUser implements Serializable{

    public $id;
    public $userName;
    public $firstName;
    public $lastName;
    public $email;

    function __construct($id, $userName, $firstName, $lastName, $email)
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    function toJson() {
        return json_encode($this);
    }

    public static function fromJson($json) {
        $mixed = json_decode($json);
        $commentUser = new CommentUser($mixed->id, $mixed->userName, $mixed->firstName, $mixed->lastName, $mixed->email);

        return commentUser;
    }
}