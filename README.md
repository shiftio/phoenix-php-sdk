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
$mediaSiloAPI = MediaSiloAPI::createFromHostCredentials($username, $password, $host);

// Start making some calls
$me = $mediaSiloAPI->me();

