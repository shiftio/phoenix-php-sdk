<?php

namespace mediasilo\me;

use mediasilo\model\Serializable;

class Me implements Serializable
{

    public $id;
    public $numericId;
    public $defaultRoleTemplateId;
    public $userName;
    public $firstName;
    public $lastName;
    public $company;
    public $email;
    public $phone;
    public $mobile;
    public $address;
    public $status;
    public $sso;
    public $ssoId;
    public $roles;

    function __construct($id,
                         $numericId,
                         $defaultRoleTemplateId,
                         $userName,
                         $firstName,
                         $lastName,
                         $company,
                         $email,
                         $phone,
                         $mobile,
                         $address,
                         $status,
                         $sso,
                         $ssoId,
                         $roles
    )
    {
        $this->id = $id;
        $this->numericId = $numericId;
        $this->defaultRoleTemplateId = $defaultRoleTemplateId;
        $this->userName = $userName;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->company = $company;
        $this->email = $email;
        $this->phone = $phone;
        $this->mobile = $mobile;
        $this->address = $address;
        $this->status = $status;
        $this->sso = $sso;
        $this->ssoId = $ssoId;
        $this->roles = $roles;
    }

    function toJson()
    {
        return json_encode($this);
    }

    public static function fromJson($json)
    {
        $mixed = json_decode($json);

        return new Me(
            $mixed->id,
            $mixed->numericId,
            $mixed->defaultRoleTemplateId,
            $mixed->userName,
            $mixed->firstName,
            $mixed->lastName,
            $mixed->company,
            $mixed->email,
            $mixed->phone,
            $mixed->mobile,
            $mixed->address,
            $mixed->status,
            $mixed->sso,
            $mixed->ssoId,
            $mixed->roles
        );
    }

    public static function fromStdClass($stdClass)
    {
        return new Me(
            $stdClass->id,
            $stdClass->numericId,
            $stdClass->defaultRoleTemplateId,
            $stdClass->userName,
            $stdClass->firstName,
            $stdClass->lastName,
            $stdClass->company,
            $stdClass->email,
            $stdClass->phone,
            $stdClass->mobile,
            $stdClass->address,
            $stdClass->status,
            $stdClass->sso,
            $stdClass->ssoId,
            $stdClass->roles
        );
    }

}