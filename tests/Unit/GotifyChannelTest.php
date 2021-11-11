<?php

namespace Tests\Unit;

use App\Channels\GotifyChannel;
use App\Models\Message;
use App\Models\User;
use App\Notifications\InboundMessageCreated;
use App\Services\Gotify\Client;
use Mockery\MockInterface;
use Tests\TestCase;
use Illuminate\Notifications\Notification;

class GotifyChannelTest extends TestCase
{
    /** @test */
    public function sends_to_gotify_as_expected()
    {
        $data = [
            'title' => 'foo',
            'message' => 'bar',
            'url' => 'biz'
        ];

        $user = User::factory()->create([
            'gotify_app_token' => 'abc123'
        ]);

        $mNotification = \Mockery::mock(
            Notification::class,
            fn (MockInterface $mock) =>
            $mock->shouldReceive('toGotify')
            ->with($user)
            ->andReturn($data)
        );

        $mGotify = \Mockery::mock(
            Client::class,
            fn (MockInterface $mock) =>
            $mock->shouldReceive('createMessage')
            ->with(
                $data['title'],
                $data['message'],
                $data['url']
            )
        );
        $this->app->instance(Client::class, $mGotify);

        $channel = new GotifyChannel();
        $channel->send($user, $mNotification);
    }

}
