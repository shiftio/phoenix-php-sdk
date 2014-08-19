<?php


namespace mediasilo\http;

class MediaSiloResourcePaths
{
    const ACCESS_TOKEN = "/";
    const ME = "/me";
    const PROJECTS = "/projects";
    const PROJECT_FOLDERS = "/projects/%s/folders";
    const PROJECT_INVITE = "/projects/invitation";
    const PROJECT_INVITE_ACCEPT = "/projects/invitation/%s/accept";
    const USERS_PROJECTS = "/users/%s/projects";
    const CLONE_PROJECTS = "/projects/%s/clone";
    const FOLDERS = "/folders";
    const SUB_FOLDERS = "/folders/%s/subfolders";
    const DISTRIBUTION_LISTS = "/distributionlists";
    const FAVORITES = "/favorites";
    const USER_LOOKUPS = "/users/keyvaluecollections";
    const ASSET_METADATA = "/assets/%s/metadata";
    const QUICK_LINK_COMMENTS = "/quicklinks/%s/assets/%s/comments";
    const QUICKLINK_COMMENTS_EXPORT = "/quicklinks/%s/assets/%s/comments.%s";
    const QUICK_LINK_SETTINGS = "/users/quicklinksettings";
    const ASSET_RATINGS = "/assets/%s/ratings";
    const PROJECT_USERS = "/projects/%s/users";
    const SAVED_SEARCHES = "/users/savedsearches";
    const USER_TAGS = "/users/%s/tags";
    const USERS = "/users";
    const USER_PREFERENCES = "/users/%s/preferences";
    const QUICKLINK = "/quicklinks";
    const PORTAL = "/portals";
    const SHARE = "/shares";
    const ASSETS = "/assets";
    const PROJECT_ASSETS = "/projects/%s/assets";
    const FOLDER_ASSETS = "/folders/%s/assets";
    const USER_PROJECT_ROLES = "/projects/%s/roles";
    const CHANNELS = "/channels";
    const TRANSCRIPTS = "/transcripts";
    const TRANSCRIPT_SERVICES = "/transcripts/services";
    const ANALYTICS = "/analytics";
    const ANALYTICS_SPECIFIC = "/analytics/%s";
    const ACCOUNT_PREFERENCES = "/account/%s/preferences";
    const PASSWORD_RESET = "/users/password/reset";
}