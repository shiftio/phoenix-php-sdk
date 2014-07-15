<?php

namespace mediasilo;

if (!function_exists('curl_init')) {
    throw new \Exception('We need cURL for the API to work. Have a look here: http://us3.php.net/curl');
}

if (!function_exists('json_decode')) {
    throw new \Exception('We need json_decode for the API to work. If you\'re running a linux distro install this package: php-pecl-json');
}

use mediasilo\http\exception\NotFoundException;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\project\ProjectProxy;
use mediasilo\http\WebClient;
use mediasilo\favorite\FavoriteProxy;
use mediasilo\project\Project;
use mediasilo\quicklink\Configuration;
use mediasilo\quicklink\QuickLink;
use mediasilo\quicklink\QuickLinkProxy;
use mediasilo\quicklink\QuickLinkCommentProxy;
use mediasilo\account\AccountPreferencesProxy;
use mediasilo\asset\AssetProxy;
use mediasilo\channel\ChannelProxy;
use mediasilo\channel\Channel;
use mediasilo\comment\Comment;
use mediasilo\share\Share;
use mediasilo\share\ShareProxy;
use mediasilo\share\email\EmailRecipient;
use mediasilo\share\email\EmailShare;
use mediasilo\transcript\TranscriptProxy;
use mediasilo\transcript\TranscriptServiceProxy;
use mediasilo\quicklink\Setting;
use mediasilo\http\oauth\TwoLeggedOauthClient;
use mediasilo\http\oauth\OAuthException;
use mediasilo\quicklink\analytics\QuickLinkAnalyticsProxy;
use mediasilo\user\UserProxy;
use mediasilo\user\User;
use mediasilo\user\PasswordReset;
use mediasilo\user\PasswordResetRequest;

class MediaSiloAPI
{
    private $webClient;
    private $favoriteProxy;
    private $projectProxy;
    private $quicklinkProxy;
    private $quicklinkAnalyticsProxy;
    private $shareProxy;
    private $assetProxy;
    private $channelProxy;
    private $transcriptProxy;
    private $transcriptServiceProxy;
    private $accountPreferencesProxy;
    private $userProxy;
    private $consumerKey;
    private $consumerSecret;
    private $baseUrl;

    private $me;

    public function __construct() {}

    private function init() {
        $this->proxyInit();
        $this->me();
    }

    private function proxyInit() {
        $this->favoriteProxy = new FavoriteProxy($this->webClient);
        $this->projectProxy = new ProjectProxy($this->webClient);
        $this->quicklinkProxy = new QuickLinkProxy($this->webClient);
        $this->shareProxy = new ShareProxy($this->webClient);
        $this->assetProxy = new AssetProxy($this->webClient);
        $this->channelProxy = new ChannelProxy($this->webClient);
        $this->transcriptProxy = new TranscriptProxy($this->webClient);
        $this->transcriptServiceProxy = new TranscriptServiceProxy($this->webClient);
        $this->quicklinkAnalyticsProxy = new QuickLinkAnalyticsProxy($this->webClient);
        $this->accountPreferencesProxy = new AccountPreferencesProxy($this->webClient);
        $this->userProxy = new UserProxy($this->webClient);
    }

    public static function createFromHostCredentials($username, $password, $host, $baseUrl = "p-api.mediasilo.com/v3") {
        $instance = new self();
        $instance->webClient = WebClient::createFromHostCredentials($username, $password, $host, $baseUrl); 
        $instance->init();
        
        return $instance;
    }

    public static function createFromSession($session, $host, $baseUrl = "phoenix.mediasilo.com/v3") {
        $instance = new self();
        $instance->webClient = WebClient::createFromSession($session, $host, $baseUrl);
        $instance->init();
        
        return $instance; 
    }

    public static function createFromApplicationConsumer($consumerKey, $consumerSecret, $baseUrl = "phoenix.mediasilo.com/v3") {
        $instance = new self();
        $instance->consumerKey = $consumerKey;
        $instance->consumerSecret = $consumerSecret;
        $instance->baseUrl = $baseUrl;
        $instance->webClient = TwoLeggedOauthClient::create2LegClient($consumerKey, $consumerSecret, $baseUrl);
        $instance->init();

        return $instance;
    }

    public function getAccessToken($username, $password, $hostname) {
        $params = array('username' => $username, 'password'=>$password, 'hostname' => $hostname, 'grant_type' => 'password');
        $response = json_decode($this->webClient->getAccessToken($params));
        $this->webClient = TwoLeggedOauthClient::create2LegProxyCredsClient($this->consumerKey, $this->consumerSecret, $response->id, $this->baseUrl);
        $this->proxyInit();

        return $response->id;
    }

    public function getAccessTokenBySession($sessionKey, $hostname) {
        $params = array('session' => $sessionKey, 'hostname' => $hostname, 'grant_type' => 'password');
        $response = json_decode($this->webClient->getAccessToken($params));
        $this->webClient = TwoLeggedOauthClient::create2LegProxyCredsClient($this->consumerKey, $this->consumerSecret, $response->id, $this->baseUrl);
        $this->proxyInit();

        return $response->id;
    }

    public function setAccessToken($accessToken) {
        if(!isset($this->consumerKey)) {
            throw new OAuthException("There is no consumer credentials set for the API instance. An access token cannot be used without consumer credentials.");
        }
        $this->webClient = TwoLeggedOauthClient::create2LegProxyCredsClient($this->consumerKey, $this->consumerSecret, $accessToken, $this->baseUrl);
        $this->proxyInit();
    }

    public function unsetAccessToken() {
        if(!isset($this->consumerKey)) {
            throw new OAuthException("There is no consumer credentials set for the API instance. An access token cannot be used without consumer credentials.");
        }
        $this->webClient = TwoLeggedOauthClient::create2LegClient($this->consumerKey, $this->consumerSecret, $this->baseUrl);
        $this->proxyInit();
    }

    public function me()
    {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::ME);
        $this->me = json_decode($clientResponse->getBody());

        return $this->me;
    }

    // Account //
    public function getAccountPreferences() {
        return $this->accountPreferencesProxy->getAccountPreferences($this->me->accountId);
    }

    public function getAccountPreference($preferenceKey) {
        return $this->accountPreferencesProxy->getAccountPreference($this->me->accountId, $preferenceKey);
    }

    public function updateAccountPreference($preferenceKey, $preferenceValue) {
        return $this->accountPreferencesProxy->updateAccountPreference($this->me->accountId, $preferenceKey, $preferenceValue);
    }

    // Projects //

    /**
     * Creates a new project. The project in MediaSilo of the given project model.
     * @param Project $project
     */
    public function createProject(Project $project)
    {
        $this->projectProxy->createProject($project);
    }

    /**
     * Gets an existing project for a given project Id.
     * @param $id
     * @return Project
     */
    public function getProject($id)
    {
        return $this->projectProxy->getProject($id);
    }

    /**
     * Gets a list of Projects
     * @return Array[Project]
     */
    public function getProjects() {
        return $this->projectProxy->getProjects();
    }

    /**
     * Updates an existing project. ID must be a valid project Id.
     * @param Project $project
     */
    public function updateProject(Project $project)
    {
        $this->projectProxy->updateProject($project);
    }

    /**
     * Deletes an existing project from a given project Id.
     * @param $id
     */
    public function deleteProject($id)
    {
        $this->projectProxy->deleteProject($id);
    }

    /**
     * Copies the structure and users of an existing project to a new project
     * @param $projectId
     * @return mixed
     */
    public function cloneProject($projectId)
    {
        return $this->projectProxy->cloneProject($projectId);
    }

    public function getUsersProjects($userId)
    {
        return $this->projectProxy->getUsersProjects($userId);
    }


    // Favorites //

    /**
     * Set a given project as one of your favorites
     * @param $projectId
     */
    public function favorProject($projectId)
    {
        $this->favoriteProxy->favorProject($projectId);
    }

    /**
     * Remove a given project from you list of favorites
     * @param $projectId
     */
    public function unfavor($projectId)
    {
        $this->favoriteProxy->unfavor($projectId);
    }

    /**
     * Get all of your favorite projects
     * @return array
     */
    public function getFavoriteProjects()
    {
        return $this->favoriteProxy->getFavoriteProjects();
    }

    // Asset //

    /**
     * Gets an asset from an asset Id.
     * @param $id
     * @param $acl (if true the ACL for the requesting user will be attached to each asset)
     * @return Asset
     */
    public function getAsset($id, $acl = false)
    {
        return $this->assetProxy->getAsset($id, $acl);
    }

    /**
     * Gets a list of assets from an array of asset Ids.
     * @param Array $ids
     * @param Boolean $acl (if true the ACL for the requesting user will be attached to each asset)
     * @return Array(Asset)
     */
    public function getAssetsByIds($ids, $acl = false) {
        return $this->assetProxy->getAssetByIds($ids, $acl);
    }

    /**
     * Gets assets in the given project
     * @param $projectId
     * @param $acl (if true the ACL for the requesting user will be attached to each asset)
     * @return Array(Asset)
     */
    public function getAssetsByProject($projectId, $acl = false)
    {
        return $this->assetProxy->getAssetsByProjectId($projectId, $acl);
    }

    /**
     * Gets assets in the given folder
     * @param $folderId
     * @param $acl (if true the ACL for the requesting user will be attached to each asset)
     * @return Array(Asset)
     */
    public function getAssetsByFolder($folderId, $acl = false)
    {
        return $this->assetProxy->getAssetsByFolderId($folderId, $acl);
    }


    // Channel //

    /**
     * Gets the channel for the given Id
     * @param $id
     * @return Channel
     */
    public function getChannel($id)
    {
        return $this->channelProxy->getChannel($id);
    }

    /**
     * Gets all channels user has access to
     * @return Array(Channel)
     */
    public function getChannels()
    {
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
     * @param $stretching
     * @param array $assets
     * @return Channel
     */
    public function createChannel($name, $autoPlay, $height, $width, $playback, $public, $stretching, array $assets) {
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
     * @param $stretching
     * @param array $assets
     * @return Channel
     */
    public function updateChannel($id, $name, $autoPlay, $height, $width, $playback, $public, $stretching, array $assets) {
        $channel = new Channel($id, $name, null, $autoPlay, $height, $width, $playback, $public, $stretching, null, $assets);
        $this->channelProxy->updateChannel($channel);

        return $channel;
    }

    /**
     * Deletes the given channel
     * @param $channelId
     */
    public function deleteChannel($channelId)
    {
        $this->channelProxy->deleteChannel($channelId);
    }


    // Transcript //

    /**
     * Gets the transcript for the given asset
     * @param $assetId
     * @return Transcript
     */
    public function getTranscript($assetId)
    {
        return $this->transcriptProxy->getTranscript($assetId);
    }


    // Transcript Service //

    /**
     * Gets all transcript services available for this user
     * @return Array(TranscriptService)
     */
    public function getTranscriptServices()
    {
        return $this->transcriptServiceProxy->getTranscriptServices();
    }

    // Quicklinks //

    /**
     * Persists a QuickLink in MediaSilo
     * NOTE! This does not send it, only creates it.
     * @param string $title Title for the Quicklink
     * @param string $description Description for the Quicklink
     * @param array $assetIds Array of Asset ID to be included in quicklink
     * @param array $settings Key/Value associative array of settings
     * @return Quicklink Hydrated model of created quicklink
     */
    public function createQuickLink($title, $description = "", array $assetIds = array(), array $settings = array())
    {
        $newSettings = array();
        foreach ($settings as $key => $value) {
            array_push($newSettings, new Setting((string)$key, (string)$value));
        }
        $configuration = new Configuration(null, $newSettings);

        $quickLink = new QuickLink($assetIds, $configuration, $description, array(), $title);
        $this->quicklinkProxy->createQuickLink($quickLink);
        return $quickLink;
    }

    /**
     * Fetches a quicklink based on UUID
     * @param String $id
     * @param Bool - true to embed analytics
     * @returns Quicklink
     */
    public function getQuickLink($id, $includeAnalytics = false)
    {
        return $this->quicklinkProxy->getQuickLink($id, $includeAnalytics);
    }

    /**
     * Fetches a list of Quicklinks
     * @param String - query params to be included with the request
     * @param Bool - true to embed analytics
     * @returns Quicklink[] Array of Quicklink Objects
     */
    public function getQuickLinks($params = null, $includeAnalytics = false)
    {
        return $this->quicklinkProxy->getQuicklinks($params, $includeAnalytics);
    }

    /**
     * Fetches a list of Quicklinks wrapped in a pagination object
     * @param $params
     * @param bool $includeAnalytics
     * @return mixed
     */
    public function getQuicklinksPaginated($params, $includeAnalytics = false) {
        if (!is_null($params)) {
            return $this->quicklinkProxy->getQuicklinks($params, $includeAnalytics, true);
        } else {
            return $this->quicklinkProxy->getQuicklinks(null, $includeAnalytics, true);
        }
    }

    /**
     * Updates a QuickLink in MediaSilo
     * @param String $id UUID of quicklink to update
     * @param String $title Title for the Quicklink
     * @param String $description Description for the Quicklink
     * @param Array $assetIds Array of Asset ID to be included in quicklink
     * @param Array $settings Key/Value associative array of settings
     * @param String $expires timestamp
     * @return Void
     */
    public function updateQuickLink($id, $title = null, $description = null, array $assetIds = null, array $settings = null, $expires = null)
    {
        $assets = null;
        $configuration = null;

        if (is_array($settings)) {
            $newSettings = array();
            foreach ($settings as $key => $value) {
                array_push($newSettings, new Setting((string)$key, (string)$value));
            }
            $configuration = new Configuration(null, $newSettings);
        } else {
            $configuration = new Configuration(null, null);
        }

        if (is_array($assetIds)) {
            $assets = $assetIds;
        }

        $quickLink = new QuickLink($assets, $configuration, $description, array(), $title);
        $quickLink->expires = $expires;
        $quickLink->setId($id);
        $this->quicklinkProxy->updateQuicklink($quickLink);
    }

    /**
     * Creates a Comment on an Asset in a Quicklink
     * @param String $quicklinkId
     * @param String $assetId
     * @param String $commentBody
     * @param String $inResponseTo
     * @param String $startTimeCode
     * @param String $endTimeCode
     * @param String $user
     * @return String
     */
    public function commentOnQuickLinkAsset($quicklinkId, $assetId, $commentBody, $inResponseTo = null, $startTimeCode = null, $endTimeCode = null, $user = null) {
        $comment = new Comment($assetId, $inResponseTo, $quicklinkId, $commentBody);
        $comment->startTimeCode = $startTimeCode;
        $comment->endTimeCode = $endTimeCode;
        $comment->user = $user;

        $resourcePath = sprintf(MediaSiloResourcePaths::QUICK_LINK_COMMENTS, $quicklinkId, $assetId);
        $result = json_decode($this->webClient->post($resourcePath, $comment->toJson()));
        return $result->id;
    }

    /**
     * Shares a QuickLink
     * @param string $quicklinkId ID of the quicklink you want to share
     * @param string $subject Subject for the email
     * @param string $message Body for the email
     * @param array $emailAddresses Email addresses (strings) of email recipients
     * @return Share object
     */
    public function shareQuickLink($quicklinkId, $subject = "", $message = "", array $emailAddresses = array()) {
        $emailRecipients = array();
        foreach($emailAddresses as $email) {
            array_push($emailRecipients, new EmailRecipient($email, null));
        }
        $emailShare = new EmailShare($emailRecipients, $message, $subject);
        $share = new Share($emailShare, null, $quicklinkId);
        $this->shareProxy->createShare($share);
        return $share;
    }

    /**
    * For a quick link that has already been shared, this will return those shares 
    * allowing you to see when and to who the quicklink was shared
    *
    * @param string $quicklinkId The quicklink for which you'd like to retrieve shares
    */
    public function getQuicklinkShares($quicklinkId) {
        return $this->shareProxy->getShares($quicklinkId);
    }

    /**
     * Gets events on a quicklink
     * @param String $quicklinkIds
     * @return Array
     */
    public function getQuicklinkAggregateEvents($quicklinkIds) {
        $quickLinkEvents = $this->quicklinkAnalyticsProxy->getQuicklinkAggregateEvents($quicklinkIds);
        return $quickLinkEvents;
    }

    /**
     * Gets a User by UUID
     * @param String $userId
     * @return Array
     */
    public function getUser($userId)
    {
        return $this->userProxy->getUser($userId);
    }

    /**
     * Persists Updates to a User Object
     * @param User $user
     */
    public function updateUser(User $user) {
        $this->userProxy->updateUser($user);
    }

    /**
     * Updates a User profile based on update-able parameters
     * @param $userId
     * @param null $firstName
     * @param null $lastName
     * @param null $username
     * @param null $email
     * @param null $password
     * @param null $address
     * @param null $phone
     * @param null $mobile
     * @param null $company
     * @param null $status
     * @param null $defaultRowTemplateId
     */
    public function updateUserProfile($userId, $firstName = null, $lastName = null, $username = null, $email = null, $password = null,
                               $address = null, $phone = null, $mobile = null, $company = null, $status = null, $defaultRowTemplateId = null)
    {
        $user = new User($address,$company,$defaultRowTemplateId, $email,$firstName, $userId, $lastName, $mobile, null, $phone, null, null, null, $status, $username, null);

        if (!is_null($password)) {
            $user->setPassword($password);
        }

        $this->userProxy->updateUser($user);
    }

    /**
     * Gets a Saved Search by UUID
     * @param String $id
     * @return Array
     */
    public function getSavedSearch($id)
    {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::SAVED_SEARCHES . "/" . $id);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Gets a list of  Saved Searches
     * @return Array
     */
    public function getSavedSearches()
    {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::SAVED_SEARCHES);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Gets an Asset's Meta Data entry by Key
     * @param String $assetId
     * @param String $key
     * @return Object
     */
    public function getAssetMetaDatum($assetId, $key)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::ASSET_METADATA, $assetId) . "/" . $key;
        $clientResponse = $this->webClient->get($resourcePath);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Gets a list of an asset's Meta Data
     * @param String $assetId
     * @return Array
     */
    public function getAssetMetaData($assetId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::ASSET_METADATA, $assetId);
        $clientResponse = $this->webClient->get($resourcePath);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Gets User Preference By User UUID and Preference Key
     * @param String $userId
     * @param String $preference
     * @return Object
     */
    public function getUserPreference($userId, $preference)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::USER_PREFERENCES, $userId) . "/" . $preference;
        $clientResponse = $this->webClient->get($resourcePath);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Get List of User Preferences by User UUID
     * @param String $userId
     * @return Array[Object]
     */
    public function getUserPreferences($userId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::USER_PREFERENCES, $userId);
        $clientResponse = $this->webClient->get($resourcePath);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Gets a list of User Tags by User UUID
     * @param String $userId
     * @return Array[Object]
     */
    public function getUsersTags($userId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::USER_TAGS, $userId);
        $clientResponse = $this->webClient->get($resourcePath);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Gets a User Key/Value Pair By UUID
     * @param String $id
     * @return Object
     */
    public function getUserKeyPair($id)
    {
        $clientResponse = $this->webClient->get($this->webClient->get(MediaSiloResourcePaths::USER_LOOKUPS . "/" . $id));
        return json_decode($clientResponse->getBody());
    }

    /**
     * Gets a list of User Key/Value Pairs
     * @return Array[Object]
     */
    public function getUserKeyPairs()
    {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::USER_LOOKUPS);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Gets a list of Distribution Lists
     * @return Array[Object]
     */
    public function getDistributionLists()
    {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::DISTRIBUTION_LISTS);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Gets a Distribution List by UUID
     * @param String $id
     * @return Object
     */
    public function getDistributionList($id)
    {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::DISTRIBUTION_LISTS . "/" . $id);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Get Folder By UUID
     * @param String $id
     * @return Object
     */
    public function getFolder($id)
    {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::FOLDERS . "/" . $id);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Gets a list of Folder's Sub-folders
     * @param String $parentFolderId
     * @return Array[Object]
     */
    public function getSubfolders($parentFolderId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::SUB_FOLDERS, $parentFolderId);
        $clientResponse = $this->webClient->get($resourcePath);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Gets a list of Project's Sub-folders
     * @param $projectId
     * @return Array[Object]
     */
    public function getProjectFolders($projectId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::PROJECT_FOLDERS, $projectId);
        $clientResponse = $this->webClient->get($resourcePath);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Get a list of a Projects's Users
     * @param String $projectId
     * @return Array[Object]
     */
    public function getProjectUsers($projectId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::PROJECT_USERS, $projectId);
        $clientResponse = $this->webClient->get($resourcePath);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Get an individual Quicklink Preset by UUID
     * @param String $settingId
     * @return Object
     */
    public function getQuickLinkSetting($settingId)
    {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::QUICK_LINK_SETTINGS . "/" . $settingId);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Get a list of Quicklink Presets
     * @return Array[Object]
     */
    public function getQuickLinkSettings()
    {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::QUICK_LINK_SETTINGS);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Get a list of Ratings by Asset UUID
     * @param String $assetId
     * @return Array[Object]
     */
    public function getAssetRatings($assetId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::ASSET_RATINGS, $assetId);
        $clientResponse = $this->webClient->get($resourcePath);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Get a list of comments on an Asset in a Quicklink
     * @param String $quickLinkId
     * @param String $assetId
     * @return Array[Object]
     */
    public function getQuickLinkComments($quickLinkId, $assetId)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::QUICK_LINK_COMMENTS, $quickLinkId, $assetId);

        $clientResponse = $this->webClient->get($resourcePath);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Get a list of comments on an Asset in a Quicklink in a specified export format
     * @param String $quickLinkId
     * @param String $assetId
     * @param String $format
     * @return Array[Object]
     */
    public function getQuickLinkCommentsExport($quickLinkId, $assetId, $format="txt")
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::QUICKLINK_COMMENTS_EXPORT, $quickLinkId, $assetId, $format);
        $clientResponse = $this->webClient->get($resourcePath);
        return $clientResponse->getBody();
    }

    /**
     * Get a list of tracked events specified by the events list and filtered by a query
     * @param Array $events
     * @param String $query
     * @return Array[Object]
     */
    public function getAnalytics($events, $query)
    {
        $resourcePath = sprintf(MediaSiloResourcePaths::ANALYTICS_SPECIFIC, join(",", $events));
        $clientResponse = json_decode($this->webClient->post($resourcePath, $query));
        return $clientResponse;
    }

    /**
     * Performs a Password Reset Request (sends password reset link with token to user's email)
     * @requires System Permission
     * @param String $hostname
     * @param String $username
     * @param String $type (optional)
     * @param String $redirectUri (optional)
     * @returns Object - ID property contains request token id
     */
    public function initiatePasswordReset($hostname, $username, $type = "reset", $redirectUri = null) {
        $request = new PasswordResetRequest($hostname, $username, $type, $redirectUri);
        return json_decode($this->webClient->post(MediaSiloResourcePaths::PASSWORD_RESET, $request->toJson()));
    }

    /**
     * Validates a Password Reset Request token is still valid
     * @requires System Permission
     * @param String $token
     * @return Object - Reset Token Representation
     */
    public function validateResetToken($token) {
        $resourcePath = sprintf("%s/%s", MediaSiloResourcePaths::PASSWORD_RESET, $token);
        $clientResponse = $this->webClient->get($resourcePath);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Performs a password update for a user associated with a valid token
     * * @requires System Permission
     * @param String $token
     * @param String $password
     * @return Object - redirectUrl property contains location to redirect to upon success
     */
    public function processPasswordReset($token, $password) {
        $request = new PasswordReset($token, $password);
        return json_decode($this->webClient->put(MediaSiloResourcePaths::PASSWORD_RESET, $request->toJson()));
    }
}
