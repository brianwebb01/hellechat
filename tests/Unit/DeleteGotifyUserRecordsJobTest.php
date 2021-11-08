<?php

namespace Tests\Unit;

use App\Jobs\DeleteGotifyUserRecordsJob;
use App\Models\User;
use App\Services\Gotify\Client;
use Mockery\MockInterface;
use Tests\TestCase;

class DeleteGotifyUserRecordsJobTest extends TestCase
{
    /** @test */
    public function deletes_gotify_records_as_expected()
    {
        $user = User::factory()->create([
            'gotify_user_id' => 123
        ]);

        $mGotify = \Mockery::mock(
            Client::class,
            fn (MockInterface $mock) =>
            $mock->shouldReceive('deleteUser')->with(123)
        );
        $this->app->instance(Client::class, $mGotify);

        $job = new DeleteGotifyUserRecordsJob($user->gotify_user_id);
        $job->handle();
    }
}
