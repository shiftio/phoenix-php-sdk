<?php

namespace mediasilo;

use mediasilo\MediaSiloResourcePaths;
use mediasilo\http\WebClient;
use stdClass;

class MediaSiloAPI
{

    private $webClient;

    public function __construct()
    {
        $this->webClient = new WebClient();
    }

    public function me()
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::ME));
    }

    public function getProject($id)
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::PROJECTS . "/" . $id));
    }

    public function getFavorite($id)
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::FAVORITES . "/" . $id));
    }

    public function getUser($userId)
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::USERS . "/" . $userId));
    }

    public function getFavorites()
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::FAVORITES));
    }

    public function getSavedSearch($id)
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::SAVED_SEARCHES . "/" . $id));
    }

    public function getSavedSearches()
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::SAVED_SEARCHES));
    }

    public function getAssetMetaDatum($assetId, $key)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::ASSET_METADATA, $assetId) . "/" . $key;
        return json_decode($this->webClient->get($resourcePath));
    }

    public function getAssetMetaData($assetId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::ASSET_METADATA, $assetId);
        return json_decode($this->webClient->get($resourcePath));
    }

    public function getUserPreference($userId, $preference)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::USER_PREFERENCES, $userId) . "/" . $preference;
        return json_decode($this->webClient->get($resourcePath));
    }

    public function getUserPreferences($userId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::USER_PREFERENCES, $userId);
        return json_decode($this->webClient->get($resourcePath));
    }

    public function getUsersTags($userId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::USER_TAGS, $userId);
        return json_decode($this->webClient->get($resourcePath));
    }

    public function getUserKeyPair($id)
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::USER_LOOKUPS . "/" . $id));
    }

    public function getUserKeyPairs()
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::USER_LOOKUPS));
    }

    public function getDistributionList()
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::DISTRIBUTION_LISTS));
    }

    public function getDistributionLists($id)
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::DISTRIBUTION_LISTS . "/" . $id));
    }

    public function getFolder($id)
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::FOLDERS . "/" . $id));
    }

    public function getSubfolders($parentFolderId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::SUB_FOLDERS, $parentFolderId);
        return json_decode($this->webClient->get($resourcePath));
    }

    public function getProjectFolders($projectId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::PROJECT_FOLDERS, $projectId);
        return json_decode($this->webClient->get($resourcePath));
    }

    public function getUsersProjects($userId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::USERS_PROJECTS, $userId);
        return json_decode($this->webClient->get($resourcePath));
    }

    public function getProjectUsers($projectId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::PROJECT_USERS, $projectId);
        return json_decode($this->webClient->get($resourcePath));
    }

    public function getQuickLinkSetting($settingId)
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::QUICK_LINK_SETTINGS . "/" . $settingId));
    }

    public function getQuickLinkSettings()
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::QUICK_LINK_SETTINGS));
    }

    public function getAssetRatings($assetId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::ASSET_RATINGS, $assetId);
        return json_decode($this->webClient->get($resourcePath));
    }

    public function getQuickLinkComment($quickLinkId, $commentId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::QUICK_LINK_COMMENTS, $quickLinkId) . "/" . $commentId;
        return json_decode($this->webClient->get($resourcePath));
    }

    public function getQuickLinkComments($quickLinkId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::QUICK_LINK_COMMENTS, $quickLinkId);
        return json_decode($this->webClient->get($resourcePath));
    }

    public function cloneProject($projectId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::CLONE_PROJECTS, $projectId);
        return json_decode($this->webClient->post($resourcePath, null));
    }

    public function createProject($name, $description = null, $isFavorite = false)
    {
        $object = new stdClass();
        $object->name = $name;
        $object->description = $description;
        $object->isFavorite = $isFavorite;

        return json_decode($this->webClient->post(MediaSiloResourcePaths::PROJECTS, json_encode($object)));
    }

    public function updateProject($id, $name, $description = null, $isFavorite = false)
    {
        $object = new stdClass();
        $object->id = $id;
        $object->name = $name;
        $object->description = $description;
        $object->isFavorite = $isFavorite;

        return json_decode($this->webClient->put(MediaSiloResourcePaths::PROJECTS, json_encode($object)));
    }

    public function deleteProject($id)
    {
        return json_decode($this->webClient->delete(MediaSiloResourcePaths::PROJECTS . "/" . $id));
    }

}