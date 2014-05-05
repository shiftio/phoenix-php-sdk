<?php

namespace mediasilo\quicklink;

use mediasilo\config\Config;
use mediasilo\model\Serializable;
use mediasilo\share\Share;

class QuickLink implements Serializable {
    public $id;
    public $title;
    public $description;
    public $assetIds;
    public $configuration;
    public $shares;
    public $ownerId;
    public $accountId;
    public $created;
    public $modified;
    public $expires;

    function __construct($assetIds, Configuration $configuration, $description, array $shares, $title)
    {
        $this->assetIds = $assetIds;
        $this->configuration = $configuration;
        $this->description = $description;
        $this->shares = $shares;
        $this->title = $title;
    }

    /**
     * @param string $id UUID for quicklink
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string UUID
     */
    public function getId()
    {
        return $this->id;
    }

    function toJson() {
        return json_encode($this);
    }

    public static function fromJson($json) {
        $mixed = json_decode($json);
        return QuickLink::fromStdClass($mixed);
    }

    public static function fromStdClass($stdClass) {
        $configuration = Configuration::fromStdClass($stdClass->configuration);

        $shares = array();
        if(isset($stdClass->shares) && $stdClass->shares != null) {
            foreach($stdClass->shares as $share) {
                array_push($shares, Share::fromStdClass($share));
            }
        }

        $quickLink = new QuickLink($stdClass->assetIds, $configuration, $stdClass->description, $shares, $stdClass->title);
        $quickLink->id = isset($stdClass->id) ? $stdClass->id : null;
        $quickLink->title = isset($stdClass->title) ? $stdClass->title : null;
        $quickLink->description = isset($stdClass->description) ? $stdClass->description : null;
        $quickLink->ownerId = isset($stdClass->ownerId) ? $stdClass->ownerId : null;
        $quickLink->accountId = isset($stdClass->accountId) ? $stdClass->accountId : null;
        $quickLink->modified = isset($stdClass->modified) ? $stdClass->modified : null;
        $quickLink->created = isset($stdClass->created) ? $stdClass->created : null;
        $quickLink->expires = isset($stdClass->expires) ? $stdClass->expires : null;

        return $quickLink;
    }

}