<?php
namespace mediasilo\user;

/**
 * Class PasswordResetRequest
 * @package mediasilo\user
 * Model Representation of the Phoenix Password Reset Request
 */
class PasswordResetRequest {

    private $hostname;
    private $username;
    private $type;
    private $redirectUri;

    public function __construct($hostname, $username, $type = "reset", $redirectUri = null)
    {
        $this->setHostname($hostname);
        $this->setUsername($username);
        $this->setType($type);
        $this->setRedirectUri($redirectUri);
    }

    /**
     * @param string $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns a JSON representation of this model
     * @return string
     */
    public function toJson(){
        $result = new \stdClass();
        $result->username = $this->getUsername();
        $result->hostname = $this->getHostname();
        $result->type = $this->getType();
        $result->redirectUrl = $this->getRedirectUri();
        return json_encode($result);
    }

}
