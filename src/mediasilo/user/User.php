<?php

namespace mediasilo\user;

use mediasilo\user\Address;

class User {
    private $id;
    private $numericId;
    private $defaultRoleTemplateId;
    private $userName;
    private $firstName;
    private $lastName;
    private $company;
    private $email;
    private $phone;
    private $mobile;
    private $address;
    private $status;
    private $sso;
    private $ssoId;
    private $roles;
    private $tags;

    function __construct(Address $address, $company, $defaultRoleTemplateId, $email, $firstName, $id, $lastName, $mobile, $numericId, $phone, array $roles, $sso, $ssoId, $status, $userName, $tags)
    {
        $this->address = $address;
        $this->company = $company;
        $this->defaultRoleTemplateId = $defaultRoleTemplateId;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->id = $id;
        $this->lastName = $lastName;
        $this->mobile = $mobile;
        $this->numericId = $numericId;
        $this->phone = $phone;
        $this->roles = $roles;
        $this->sso = $sso;
        $this->ssoId = $ssoId;
        $this->status = $status;
        $this->userName = $userName;
    }


    public function setAddress(Address $address)
    {
        $this->address = $address;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setDefaultRoleTemplateId($defaultRoleTemplateId)
    {
        $this->defaultRoleTemplateId = $defaultRoleTemplateId;
    }

    public function getDefaultRoleTemplateId()
    {
        return $this->defaultRoleTemplateId;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setNumericId($numericId)
    {
        $this->numericId = $numericId;
    }

    public function getNumericId()
    {
        return $this->numericId;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setSso($sso)
    {
        $this->sso = $sso;
    }

    public function getSso()
    {
        return $this->sso;
    }

    public function setSsoId($ssoId)
    {
        $this->ssoId = $ssoId;
    }

    public function getSsoId()
    {
        return $this->ssoId;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    public function getUserName()
    {
        return $this->userName;
    }


}