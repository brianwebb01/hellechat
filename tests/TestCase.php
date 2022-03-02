<?php

namespace Tests;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        //remove gotify api calls setup in UserObserver
        $ed = User::getEventDispatcher();
        $ed->forget('eloquent.created: '.User::class);
        $ed->forget('eloquent.deleted: '.User::class);

        //make sure no real calls get out to Twilio in tests
        $this->app->bind(\Twilio\Rest\Client::class, function ($app) {
            return \Mockery::mock(\Twilio\Rest\Client::class);
        });
    }
}
