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

This will install composer into the SDK. Checkout [composer](https://getcomposer.org) for more details on installation options.

Check to see that composer has been installed:

    php composer.phar --version

Install the SDK's dependencies:

    php composer.phar install
    
#### Usage

    // Set your credentials
    $username = "PoohBear";
    $password = "T!gger!sPushy";
    $host = "100acreforest";
    
    // Instantiate client
    $mediasiloapi = new MediaSiloAPI($username, $password, $host);
    
    // Start making some calls
    $me = $mediasiloapi->me();
