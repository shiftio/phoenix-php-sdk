<?php

use mediasilo\MediaSiloAPI;
use mediasilo\http\ratelimit\RateLimitException;

class WebClientTests extends PHPUnit_Framework_TestCase
{


    public function testCanBeNegated()
    {
        $mediaSiloAPI = new MediaSiloAPI();

        try {
            var_dump($mediaSiloAPI->deleteProject("d8114bef-72df-4344-828f-6ad6d33f9594"));
        }
        catch(ValidationException $e) {
            echo($e);
        }
    }

}
