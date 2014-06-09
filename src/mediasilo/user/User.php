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
    private $accountId;
    private $status;
    private $sso;
    private $ssoId;
    private $roles;
    private $tags;
    private $password;

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

    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }

    public function getAccountId()
    {
        return $this->accountId;
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

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public static function fromJson($json) {
        $mixed = json_decode($json);
        return new User($mixed->address, $mixed->company, $mixed->defaultRoleTemplateId, $mixed->email, $mixed->firstName,
            $mixed->id, $mixed->lastName, $mixed->mobile, $mixed->numericId, $mixed->phone, $mixed->roles, $mixed->sso,
            $mixed->ssoId, $mixed->status, $mixed->userName, $mixed->tags);
    }

    public function toJson() {
        $responseObj = new \stdClass();
        $responseObj->id = $this->getId();
        $responseObj->numericId = $this->getNumericId();
        $responseObj->defaultRoleTemplateId = $this->getDefaultRoleTemplateId();
        $responseObj->userName = $this->getUserName();
        $responseObj->firstName = $this->getFirstName();
        $responseObj->lastName = $this->getLastName();
        $responseObj->company = $this->getCompany();
        $responseObj->email = $this->getEmail();
        $responseObj->phone = $this->getPhone();
        $responseObj->mobile = $this->getMobile();
        $responseObj->address = $this->getAddress();
        $responseObj->accountId = $this->getAccountId();
        $responseObj->status = $this->getStatus();
        $responseObj->sso = $this->getSso();
        $responseObj->ssoId = $this->getSsoId();
        $responseObj->roles = $this->getRoles();
        $responseObj->tags = $this->getTags();
        $responseObj->password = $this->getPassword();
        return json_encode($responseObj);
    }
}