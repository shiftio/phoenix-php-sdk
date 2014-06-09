<?php
namespace mediasilo\user;

/**
 * Class PasswordReset
 * @package mediasilo\user
 * Model Representation of the Phoenix Password Reset
 */
class PasswordReset {

    private $tokenId;
    private $password;

    function __construct($tokenId, $password)
    {
        $this->tokenId = $tokenId;
        $this->password = $password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $tokenId
     */
    public function setTokenId($tokenId)
    {
        $this->tokenId = $tokenId;
    }

    /**
     * @return mixed
     */
    public function getTokenId()
    {
        return $this->tokenId;
    }

    /**
     * Returns a JSON representation of this model
     * @return string
     */
    public function toJson(){
        $result = new \stdClass();
        $result->tokenId = $this->getTokenId();
        $result->password = $this->getPassword();
        return json_encode($result);
    }
}
