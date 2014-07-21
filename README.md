MediaSilo PHP SDK
===============
#### Overview

MediaSilo makes it easy to share and collaborate with your team and customers anywhere. Check out our full feature set [here](https://www.mediasilo.com/features.php)!

#### Requirements

The SDK uses Composer for dependency management and auto loading. After you've [downloaded](https://github.com/mediasilo/phoenix-php-sdk/archive/0.1.0.zip) this SDK move into it's root and install composer:

    cd <INSTALL_DIRECTORY>
    curl -sS https://getcomposer.org/installer | php

This will install composer into the SDK. Checkout [Composer](https://getcomposer.org) for more details on installation options.

Check to see that composer has been installed:

    php composer.phar --version

Install the SDK's dependencies:

    php composer.phar install

#### Install

To use a development copy of this SDK in a project, setup your composer.json as follows, where ```dev-{branch-name}`` include the branch name and ```{project-path}``` is the path to your project.

```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/mdelano/oauth-php"
        }
    ],
    "require" : {
        "mediasilo/phoenix-php-sdk": "0.6.1"
    }
}
```

#### Usage

```
<?php

require_once('vendor/autoload.php');

use mediasilo\MediaSiloAPI;

// Set your credentials
$username = "PoohBear";
$password = "T!gger!sPushy";
$host = "100acreforest";

// Instantiate client
try {
    $mediaSiloApi = MediaSiloAPI::createFromHostCredentials($username, $password, $host);
}
catch(\mediasilo\http\exception\NotAuthenticatedException $e) {
    print "Bad credentials. Cat on the keyboard? \n";
    exit;
}

// Start making some calls
$me = $mediaSiloAPI->me();
```
#### Examples

##### Get Assets By Folder

```
<?php

require_once('vendor/autoload.php');

use mediasilo\MediaSiloAPI;
use mediasilo\http\exception\NotFoundException;
use mediasilo\http\exception\NotAuthenticatedException;

// Set your credentials
$username = "PoohBear";
$password = "T!gger!sPushy";
$host = "100acreforest";

// Initialize the API
try {
    $mediaSiloApi = MediaSiloAPI::createFromHostCredentials($username, $password, $host);
}
catch(\mediasilo\http\exception\NotAuthenticatedException $e) {
    print "Bad credentials. Cat on the keyboard? \n";
    exit;
}

// Here's the project we're interested in traversing
$projectId = "07706DCC-014B-2CE0-CF518D31A23C393E";

// Let's find everything at the root of the projects
$rootLevelAssets = $mediaSiloApi->getAssetsByProject($projectId);
$rootLevelFolders = $mediaSiloApi->getProjectFolders($projectId);

// Ok, now let's traverse the prject to find the rest of the assets
foreach($rootLevelFolders as $folder) {
    get_folder_contents($mediaSiloApi, $folder->id);
}

function get_folder_contents($mediaSiloApi, $folderId) {
    print "FolderId:".$folderId."\n";
    try {
        $assets = $mediaSiloApi->getAssetsByFolder($folderId);
        var_dump($assets);
    }
    catch(NotFoundException $e) {
        print "There are not assets in this folder. Better get cracking and add some! \n";
    }

    try {
        $subfolders = $mediaSiloApi->getSubfolders($folderId);

        foreach($subfolders as $subfolder) {
            get_folder_contents($mediaSiloApi, $subfolder->id);
        }
    }
    catch(NotFoundException $e) {
        print "No more folders here!";
    }
}
```
