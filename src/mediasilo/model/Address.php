<?php

namespace mediasilo\model;

class Address {

    private $address1;
    private $address2;
    private $city;
    private $province;
    private $postalCode;
    private $country;

    function __construct($address1, $address2, $city, $country, $postalCode, $province)
    {
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->city = $city;
        $this->country = $country;
        $this->postalCode = $postalCode;
        $this->province = $province;
    }

    public function setAddress1($address1)
    {
        $this->address1 = $address1;
    }

    public function getAddress1()
    {
        return $this->address1;
    }

    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    }

    public function getAddress2()
    {
        return $this->address2;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function setProvince($province)
    {
        $this->province = $province;
    }

    public function getProvince()
    {
        return $this->province;
    }
}