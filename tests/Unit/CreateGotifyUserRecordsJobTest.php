<?php

namespace Tests\Unit;

use App\Jobs\CreateGotifyUserRecordsJob;
use App\Models\User;
use App\Services\Gotify\Client;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateGotifyUserRecordsJobTest extends TestCase
{
    /** @test */
    public function creates_records_as_expected()
    {
        $user = User::factory()->create();
        $this->assertNull($user->gotify_user_id);
        $this->assertNull($user->gotify_user_pass);
        $this->assertNull($user->gotify_client_id);
        $this->assertNull($user->gotify_client_token);
        $this->assertNull($user->gotify_app_id);
        $this->assertNull($user->gotify_app_token);

        $mGotify = \Mockery::mock(
            Client::class,
            fn (MockInterface $mock) =>
            $mock->shouldReceive('createUser')
                ->with('fooUser', 'password')
                ->andReturn([
                    'id' => 1
                ])
                ->shouldReceive('createClient')->with(config('app.name'))
                ->andReturn([
                    'id' => 2,
                    'token' => 'client-token'
                ])
                ->shouldReceive('createApplication')->with(config('app.name'))
                ->andReturn([
                    'id' => 3,
                    'token' => 'app-token'
                ])
                ->shouldReceive('updateApplicationImage')
                ->with(3, public_path('images/speech-bubble.png'))
        );
        $this->app->instance(Client::class, $mGotify);

        $job = new CreateGotifyUserRecordsJob($user);
        $job->handle('fooUser', 'password');

        $user->refresh();
        $this->assertEquals(1, $user->gotify_user_id);
        $this->assertEquals('fooUser', $user->gotify_user_name);
        $this->assertEquals('password', $user->gotify_user_pass);
        $this->assertEquals(2, $user->gotify_client_id);
        $this->assertEquals('client-token', $user->gotify_client_token);
        $this->assertEquals(3, $user->gotify_app_id);
        $this->assertEquals('app-token', $user->gotify_app_token);
    }

    /** @test */
    public function generates_user_name_as_expected()
    {
        $user = User::factory()->create();
        $job = new CreateGotifyUserRecordsJob($user);
        $name = $job->generateUserName();
        list($adj, $animal, $num) = explode('-', $name);

        $this->assertDatabaseHas('words_adjectives', ['word' => $adj]);
        $this->assertDatabaseHas('words_animals', ['word' => $animal]);
        $this->assertTrue($num <= 9);
        $this->assertTrue($num >= 1);
    }
}
