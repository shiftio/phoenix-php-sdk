<?php

namespace mediasilo\portal;

use mediasilo\model\Serializable;

class Portal implements Serializable {

    public $accountId;
    public $channels;
    public $configuration;
    public $created;
    public $description;
    public $expires;
    public $id;
    public $modified;
    public $ownerId;
    public $private;
    public $title;
    public $url;

    function __construct($title, $channels, $configuration, $description = "", $expires = null) {
        $this->title = $title;
        $this->channels = $channels;
        $this->configuration = $configuration;
        $this->description = $description;
        $this->expires = $expires;
    }

    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function setChannels($channels)
    {
        $this->channels = $channels;
    }

    public function getChannels()
    {
        return $this->channels;
    }

    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setExpires($expires)
    {
        $this->expires = $expires;
    }

    public function getExpires()
    {
        return $this->expires;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function setPrivate($private)
    {
        $this->private = $private;
    }

    public function getPrivate()
    {
        return $this->private;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    function toJson() {
        return json_encode($this);
    }

    public static function fromJson($json) {
        $mixed = json_decode($json);
        return Portal::fromStdClass($mixed);
    }

    public static function fromStdClass($stdClass) {
        if($stdClass != null) {
            $accountId = isset($stdClass->accountId) ? $stdClass->accountId : null;
            $channels = isset($stdClass->channels) ? $stdClass->channels : null;
            $configuration = isset($stdClass->configuration) ? $stdClass->configuration : null;
            $created = isset($stdClass->created) ? $stdClass->created : null;
            $description = isset($stdClass->description) ? $stdClass->description : null;
            $expires = isset($stdClass->expires) ? $stdClass->expires : null;
            $id = isset($stdClass->id) ? $stdClass->id : null;
            $modified = isset($stdClass->modified) ? $stdClass->modified : null;
            $ownerId = isset($stdClass->ownerId) ? $stdClass->ownerId : null;
            $private = isset($stdClass->private) ? $stdClass->private : null;
            $title = isset($stdClass->title) ? $stdClass->title : null;
            $url = isset($stdClass->url) ? $stdClass->url : null;

            $portal = new Portal($title, $channels, $configuration, $description, $expires);
            $portal->setAccountId($accountId);
            $portal->setCreated($created);
            $portal->setId($id);
            $portal->setModified($modified);
            $portal->setOwnerId($ownerId);
            $portal->setPrivate($private);
            $portal->setUrl($url);
            return $portal;
        }
        return null;
    }
}
