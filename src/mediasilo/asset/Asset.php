<?php

namespace mediasilo\asset;

use mediasilo\model\Serializable;

class Asset implements Serializable {

    public $id;
    public $title;
    public $description;
    public $filename;
    public $dateCreated;
    public $dateModified;
    public $folderid;
    public $projectId;
    public $transcriptStatus;
    public $approvalStatus;
    public $uploadedBy;
    public $archiveStatus;
    public $type;
    public $progress;
    public $myRating;
    public $avergeRating;
    public $private;
    public $external;
    public $tags;
    public $derivatives;
    public $acl;


    function __construct($id, 
                         $title, 
                         $description, 
                         $filename, 
                         $dateCreated,
                         $dateModified, 
                         $folderid, 
                         $projectId,
                         $transcriptstatus,
                         $approvalstatus,
                         $uploadedby,
                         $archivestatus,
                         $type,
                         $progress,
                         $myrating,
                         $avergerating,
                         $private,
                         $external,
                         $tags,
                         $derivatives)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->filename = $filename;
        $this->dateCreated = $dateCreated;
        $this->dateModified = $dateModified;
        $this->folderid = $folderid;
        $this->projectId = $projectId;
        $this->transcriptstatus = $transcriptstatus;
        $this->approvalstatus = $approvalstatus;
        $this->uploadedby = $uploadedby;
        $this->archivestatus = $archivestatus;
        $this->type = $type;
        $this->progress = $progress;
        $this->myrating = $myrating;
        $this->avergerating = $avergerating;
        $this->private = $private;
        $this->external = $external;
        $this->tags = $tags;
        $this->derivatives = $derivatives;
    }

    function toJson() {
        return json_encode($this);
    }

    public static function fromJson($json) {
    	$mixed = json_decode($json);
        return new Asset($mixed->id,
                         $mixed->title,
                         $mixed->description,
                         $mixed->filename,
                         $mixed->dateCreated,
                         $mixed->dateModified,
                         $mixed->folderid,
                         $mixed->projectId,
                         $mixed->transcriptStatus,
                         $mixed->approvalstatus,
                         $mixed->uploadedBy,
                         $mixed->archiveStatus,
                         $mixed->type,
                         $mixed->progress,
                         $mixed->myRating,
                         $mixed->avergeRating,
                         $mixed->private,
                         $mixed->external,
                         $mixed->tags,
                         $mixed->derivatives);
    }

    public static function fromStdClass($stdClass) {
        return new Asset($stdClass->id,
                         $stdClass->title,
                         $stdClass->description,
                         $stdClass->filename,
                         $stdClass->dateCreated,
                         $stdClass->dateModified,
                         $stdClass->folderid,
                         $stdClass->projectId,
                         $stdClass->transcriptStatus,
                         $stdClass->approvalstatus,
                         $stdClass->uploadedBy,
                         $stdClass->archiveStatus,
                         $stdClass->type,
                         $stdClass->progress,
                         $stdClass->myRating,
                         $stdClass->avergeRating,
                         $stdClass->private,
                         $stdClass->external,
                         $stdClass->tags,
                         $stdClass->derivatives);
    }

}