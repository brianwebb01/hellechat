<?php

namespace Tests\Feature\Notifications;

use App\Channels\GotifyChannel;
use App\Models\Message;
use App\Notifications\InboundMessageCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InboundMessageCreatedTest extends TestCase
{
    /**
     * @test
     */
    public function notification_functions_as_expected()
    {
        $message = Message::factory()->create([
            'direction' => Message::DIRECTION_IN
        ]);
        $user = $message->user;
        $user->gotify_app_token = 'abc123';
        $user->save();

        $notification = new InboundMessageCreated($message);
        $this->assertEquals($message, $notification->toGotify($user));
        $this->assertEquals(['message' => $message->toArray()], $notification->toArray($user));
        $this->assertTrue(in_array(GotifyChannel::class, $notification->via($user)));
    }

    /**
     * @test
     */
    public function gotify_channel_missing_as_expected()
    {
        $message = Message::factory()->create([
            'direction' => Message::DIRECTION_IN
        ]);

        //default user factory has no 'gotify_app_token'
        $user = $message->user;

        $notification = new InboundMessageCreated($message);
        $this->assertFalse(in_array(GotifyChannel::class, $notification->via($user)));
    }
}
