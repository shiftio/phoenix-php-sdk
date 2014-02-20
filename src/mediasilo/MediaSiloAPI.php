<?php

namespace mediasilo;

require 'vendor/autoload.php';

if (!function_exists('curl_init')) {
    throw new Exception('We need cURL for the API to work. Have a look here: http://us3.php.net/curl');
}

if (!function_exists('json_decode')) {
    throw new Exception('We need json_decode for the API to work. If you\'re running a linux distro install this package: php-pecl-json');
}

use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\project\ProjectProxy;
use mediasilo\http\WebClient;
use mediasilo\favorite\FavoriteProxy;
use mediasilo\project\Project;
use mediasilo\quicklink\Configuration;
use mediasilo\quicklink\QuickLink;
use mediasilo\quicklink\QuickLinkProxy;
use mediasilo\asset\AssetProxy;
use mediasilo\channel\ChannelProxy;
use mediasilo\channel\Channel;
use mediasilo\transcript\TranscriptProxy;
use mediasilo\transcript\TranscriptServiceProxy;

class MediaSiloAPI {
    private $webClient;
    private $favoriteProxy;
    private $projectProxy;
    private $quicklinkProxy;
    private $assetProxy;
    private $channelProxy;
    private $transcriptProxy;
    private $transcriptServiceProxy;

    public function __construct($username, $password, $host) {
        $this->webClient = new WebClient($username, $password, $host);
        $this->favoriteProxy = new FavoriteProxy($this->webClient);
        $this->projectProxy = new ProjectProxy($this->webClient);
        $this->quicklinkProxy = new QuickLinkProxy($this->webClient);
        $this->assetProxy = new AssetProxy($this->webClient);
        $this->channelProxy = new ChannelProxy($this->webClient);
        $this->transcriptProxy = new TranscriptProxy($this->webClient);
        $this->transcriptServiceProxy = new TranscriptServiceProxy($this->webClient);
    }

    public function me() {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::ME));
    }


    // Projects //

    /**
     * Creates a new project. The project in MediaSilo of the given project model.
     * @param Project $project
     */
    public function createProject(Project $project) {
        $this->projectProxy->createProject($project);
    }

    /**
     * Gets an existing project for a given project Id.
     * @param $id
     * @return Project
     */
    public function getProject($id) {
        return $this->projectProxy->getProject($id);
    }

    /**
     * Updates an existing project. ID must be a valid project Id.
     * @param Project $project
     */
    public function updateProject(Project $project) {
        $this->projectProxy->updateProject($project);
    }

    /**
     * Deletes an existing project from a given project Id.
     * @param $id
     */
    public function deleteProject($id) {
        $this->projectProxy->deleteProject($id);
    }

    /**
     * Copies the structure and users of an existing project to a new project
     * @param $projectId
     * @return mixed
     */
    public function cloneProject($projectId) {
        return $this->projectProxy->cloneProject($projectId);
    }

    public function getUsersProjects($userId) {
        return $this->projectProxy->getUsersProjects($userId);
    }


    // Favorites //

    /**
     * Set a given project as one of your favorites
     * @param $projectId
     */
    public function favorProject($projectId) {
        $this->favoriteProxy->favorProject($projectId);
    }

    /**
     * Remove a given project from you list of favorites
     * @param $projectId
     */
    public function unfavor($projectId) {
        $this->favoriteProxy->unfavor($projectId);
    }

    /**
     * Get all of your favorite projects
     * @return array
     */
    public function getFavoriteProjects() {
        return $this->favoriteProxy->getFavoriteProjects();
    }


    // Asset //

    /**
     * Gets an asset from an asset Id.
     * @param $id
     * @param $acl (if true the ACL for the requesting user will be attached to each asset)
     * @return Asset
     */
    public function getAsset($id, $acl = false) {
        return $this->assetProxy->getAsset($id, $acl);
    }

    /**
     * Gets assets in the given project
     * @param $projectId
     * @param $acl (if true the ACL for the requesting user will be attached to each asset)
     * @return Array(Asset)
     */
    public function getAssetsByProject($projectId, $acl = false) {
        return $this->assetProxy->getAssetsByProjectId($projectId, $acl);
    }

    /**
     * Gets assets in the given folder
     * @param $folderId
     * @param $acl (if true the ACL for the requesting user will be attached to each asset)
     * @return Array(Asset)
     */
    public function getAssetsByFolder($folderId, $acl = false) {
        return $this->assetProxy->getAssetsByFolderId($folderId, $acl);
    }


    // Channel //

    /**
     * Gets the channel for the given Id
     * @param $id
     * @return Channel
     */
    public function getChannel($id) {
        return $this->channelProxy->getChannel($id);
    }

    /**
     * Gets all channels user has access to
     * @return Array(Channel)
     */
    public function getChannels() {
        return $this->channelProxy->getChannels();
    }

    /**
     * Creates a new channel
     * @param $name, 
     * @param $autoPlay
     * @param $height
     * @param $width
     * @param $playback
     * @param $public
     * @param $streatching
     * @param array $assets
     */
    public function createChannel($name, $autoPlay, $height, $width, $playback, $public, $streatching, array $assets) {
        $channel = new Channel(null, $name, null, $autoPlay, $height, $width, $playback, $public, $stretching, null, $assets);
        $this->channelProxy->createChannel($channel);

        return $channel;
    }

    /**
     * Updates a channel with the given Id
     * @param $id, 
     * @param $name, 
     * @param $autoPlay
     * @param $height
     * @param $width
     * @param $playback
     * @param $public
     * @param $streatching
     * @param array $assets
     */
    public function updateChannel($id, $name, $autoPlay, $height, $width, $playback, $public, $streatching, array $assets) {
        $channel = new Channel($id, $name, null, $autoPlay, $height, $width, $playback, $public, $stretching, null, $assets);
        $this->channelProxy->updateChannel($channel);

        return $channel;
    }

    /**
     * Deletes the given channel
     * @param $channelId
     */
    public function deleteChannel($channelId) {
        $this->channelProxy->deleteChannel($channelId);
    }


    // Transcript //

    /**
     * Gets the transcript for the given asset
     * @param $assetId
     * @return Transcript
     */
    public function getTranscript($assetId) {
        return $this->transcriptProxy->getTranscript($assetId);
    }


    // Transcript Service //

    /**
     * Gets all transcript services available for this user
     * @return array(TranscriptService)
     */
    public function getTranscriptServices() {
        return $this->transcriptServiceProxy->getTranscriptServices();
    }





    public function createQuickLink($title, $description, array $assetIds, Configuration $configuration) {
        $quickLink = new QuickLink($assetIds, $configuration, $description, array(), $title);
        $this->quicklinkProxy->createQuickLink($quickLink);
    }

    public function getQuickLink($id) {
        return $this->quicklinkProxy->getQuickLink($id);
    }

    public function getUser($userId)
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::USERS . "/" . $userId));
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

}