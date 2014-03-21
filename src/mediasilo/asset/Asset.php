<?php

namespace mediasilo\asset;

use mediasilo\model\Serializable;

class Asset implements Serializable
{

    public $id;
    public $title;
    public $description;
    public $fileName;
    public $dateCreated;
    public $dateModified;
    public $folderId;
    public $projectId;
    public $transcriptStatus;
    public $approvalStatus;
    public $uploadedBy;
    public $archiveStatus;
    public $type;
    public $progress;
    public $myRating;
    public $averageRating;
    public $private;
    public $external;
    public $tags;
    public $derivatives;
    public $acl;
    public $commentCount;


    function __construct($id,
                         $title,
                         $description,
                         $fileName,
                         $dateCreated,
                         $dateModified,
                         $folderId,
                         $projectId,
                         $transcriptStatus,
                         $approvalStatus,
                         $uploadedBy,
                         $archiveStatus,
                         $type,
                         $progress,
                         $myRating,
                         $averageRating,
                         $private,
                         $external,
                         $tags,
                         $derivatives,
                         $commentCount
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->fileName = $fileName;
        $this->dateCreated = $dateCreated;
        $this->dateModified = $dateModified;
        $this->folderId = $folderId;
        $this->projectId = $projectId;
        $this->transcriptStatus = $transcriptStatus;
        $this->approvalStatus = $approvalStatus;
        $this->uploadedBy = $uploadedBy;
        $this->archiveStatus = $archiveStatus;
        $this->type = $type;
        $this->progress = $progress;
        $this->myRating = $myRating;
        $this->averageRating = $averageRating;
        $this->private = $private;
        $this->external = $external;
        $this->tags = $tags;
        $this->derivatives = $derivatives;
        $this->commentCount = $commentCount;
    }

    function toJson()
    {
        return json_encode($this);
    }

    public static function fromJson($json)
    {
        $mixed = json_decode($json);
        return new Asset($mixed->id,
            $mixed->title,
            $mixed->description,
            $mixed->fileName,
            $mixed->dateCreated,
            $mixed->dateModified,
            $mixed->folderId,
            $mixed->projectId,
            $mixed->transcriptStatus,
            $mixed->approvalStatus,
            $mixed->uploadedBy,
            $mixed->archiveStatus,
            $mixed->type,
            $mixed->progress,
            $mixed->myRating,
            $mixed->averageRating,
            $mixed->private,
            $mixed->external,
            $mixed->tags,
            $mixed->derivatives,
            $mixed->commentCount
        );
    }

    public static function fromStdClass($stdClass)
    {
        if (empty($stdClass->transcriptStatus)) {
            $stdClass->transcriptStatus = 'N/A';
        }

        return new Asset($stdClass->id,
            $stdClass->title,
            $stdClass->description,
            $stdClass->fileName,
            $stdClass->dateCreated,
            $stdClass->dateModified,
            $stdClass->folderId,
            $stdClass->projectId,
            $stdClass->transcriptStatus,
            $stdClass->approvalStatus,
            $stdClass->uploadedBy,
            $stdClass->archiveStatus,
            $stdClass->type,
            $stdClass->progress,
            $stdClass->myRating,
            $stdClass->averageRating,
            $stdClass->private,
            $stdClass->external,
            $stdClass->tags,
            $stdClass->derivatives,
            $stdClass->commentCount
        );
    }
}

