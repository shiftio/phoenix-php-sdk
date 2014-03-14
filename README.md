MediaSilo PHP SDK
===============
#### Overview

MediaSilo makes it easy to share and collaborate with your team and customers anywhere. Check out our full feature set [here](https://www.mediasilo.com/features.php)!

#### Download

Download the latest SDK here: [Version 0.1.0](https://github.com/mediasilo/phoenix-php-sdk/archive/0.1.0.zip) 

#### Requirements

The SDK uses Composer for dependency management and auto loading. After you've [downloaded](https://github.com/mediasilo/phoenix-php-sdk/archive/0.1.0.zip) this SDK move into it's root and install composer:

    cd <INSTALL_DIRECTORY>
    curl -sS https://getcomposer.org/installer | php

This will install composer into the SDK. Checkout [Composer](https://getcomposer.org) for more details on installation options.

Check to see that composer has been installed:

    php composer.phar --version

Install the SDK's dependencies:

    php composer.phar install

#### Development

To use a development copy of this SDK in a project, setup your composer.json as follows, where ```dev-{branch-name}`` include the branch name and ```{project-path}``` is the path to your project.

```
{
    "repositories": [
        {
            "type": "vcs",
            "url" : "{project-path}/phoenix-php-sdk"
        }
    ],
    "require": {
        "guzzle/guzzle"            : "~3.7",
        "mediasilo/phoenix-php-sdk": "dev-{branch-name}"
    }
}
```

#### Usage

```
use mediasilo\MediaSiloApi;

// Set your credentials
$username = "PoohBear";
$password = "T!gger!sPushy";
$host = "100acreforest";

// Instantiate client
$mediaSiloAPI = new MediaSiloAPI($username, $password, $host);

// Start making some calls
$me = $mediaSiloAPI->me();
```

It is also possible to use the SDK given a session key and API base endpoint:

```
use mediasilo\MediaSiloApi;

// Set your credentials
$sessionKey = 'xxx';
$host = 'xxx';
$baseUrl = 'phoenix.mediasilo.com/v3';

// Instantiate client
$mediaSiloAPI = new MediaSiloAPI(null, null, $host, $sessionKey, $baseUrl);

// Start making some calls
$me = $mediaSiloAPI->me();
```

##### Exception Handling

```
use mediasilo\http\exception;
use mediasilo\MediaSiloApi;

try {
    $mediaSiloAPI = new MediaSiloAPI(null, null, $host, $sessionKey, $baseUrl);
    
    $projectId = 'xxx';

    $assets = $mediaSiloAPI->getAssetsByProject($projectId);
} catch (NotFoundException $e) {
    // Resource wasn't found exception
} catch (RateLimitException $e) {
    // Too many API requests exception
} catch (ConnectionException $e) {
    // Connection issues
} catch (NotAuthorizedException $e) {
    // Permission authorization exception
} catch (ValidationException $e) {
    // Unfit POST/PUT data exception
} catch (Exception $e) {

}
```
