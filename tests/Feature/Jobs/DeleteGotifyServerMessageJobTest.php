<?php

namespace Tests\Feature\Jobs;

use App\Jobs\DeleteGotifyServerMessageJob;
use App\Services\Gotify\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class DeleteGotifyServerMessageJobTest extends TestCase
{
    /** @test */
    public function sends_delete_request_as_expected()
    {
        $mGotify = \Mockery::mock(
            Client::class,
            fn (MockInterface $mock) => $mock->shouldReceive('deleteMessage')
            ->with(123)
        );
        $this->app->instance(Client::class, $mGotify);

        $job = new DeleteGotifyServerMessageJob('token', 123);
        $job->handle();
    }
}
