<?php

namespace mediasilo;

class MediaSiloResourcePaths
{

    const ME = "/me";
    const PROJECTS = "/projects";
    const PROJECT_FOLDERS = "/projects/%s/folders";
    const USERS_PROJECTS = "/users/%s/projects";
    const CLONE_PROJECTS = "/projects/{id}/clone";
    const FOLDERS = "/folders";
    const SUB_FOLDERS = "/folders/%s/subfolders";
    const DISTRIBUTION_LISTS = "/distributionlists";
    const FAVORITES = "/favorites";
    const USER_LOOKUPS = "/users/keyvaluecollections";
    const ASSET_METADATA = "/assets/%s/metadata";
    const QUICK_LINK_COMMENTS = "/quicklinks/%s/comments";
    const QUICK_LINK_SETTINGS = "/users/quicklinksettings";
    const ASSET_RATINGS = "/assets/%s/ratings";
    const PROJECT_USERS = "/projects/%s/users";
    const SAVED_SEARCHES = "/users/savedsearches";
    const USER_TAGS = "/users/%s/tags";
    const USERS = "users";
    const USER_PREFERENCES = "/users/%s/preferences";

}