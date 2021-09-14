<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        //make sure no real calls get out to Twilio in tests
        $this->app->bind(\Twilio\Rest\Client::class, function ($app) {
            return \Mockery::mock(\Twilio\Rest\Client::class);
        });
    }
}
