<?php

namespace Tests\Unit;

use App\Channels\GotifyChannel;
use App\Models\Message;
use App\Notifications\InboundMessageCreated;
use App\Services\Gotify\Client;
use Mockery\MockInterface;
use Tests\TestCase;

class GotifyChannelTest extends TestCase
{
    /** @test */
    public function sends_to_gotify_as_expected()
    {
        $message = Message::factory()->create([
            'direction' => Message::DIRECTION_IN,
            'media' => [],
            'num_media' => 0,
            'body' => 'foobar',
        ]);
        $user = $message->user;
        $user->gotify_app_token = 'abc123';
        $user->save();

        $mGotify = \Mockery::mock(
            Client::class,
            fn (MockInterface $mock) =>
            $mock->shouldReceive('createMessage')
            ->with(
                "SMS from " . $message->contact->friendlyName(),
                $message->body,
                route('ui.thread.index', [
                    'numberPhone' => $message->number->phone_number,
                    'with' => $message->from
                ])
            )
        );
        $this->app->instance(Client::class, $mGotify);

        $notification = new InboundMessageCreated($message);
        $channel = new GotifyChannel();
        $channel->send($user, $notification);
    }


    /** @test */
    public function sends_attachment_to_gotify_as_expected()
    {
        $message = Message::factory()->create([
            'direction' => Message::DIRECTION_IN,
            'media' => ['http://somewhere.com/some/image.jpg'],
            'num_media' => 1,
            'body' => null,
        ]);
        $user = $message->user;
        $user->gotify_app_token = 'abc123';
        $user->save();

        $mGotify = \Mockery::mock(
            Client::class,
            fn (MockInterface $mock) =>
            $mock->shouldReceive('createMessage')
            ->with(
                "SMS from " . $message->contact->friendlyName(),
                "Attachment",
                route('ui.thread.index', [
                    'numberPhone' => $message->number->phone_number,
                    'with' => $message->from
                ])
            )
        );
        $this->app->instance(Client::class, $mGotify);

        $notification = new InboundMessageCreated($message);
        $channel = new GotifyChannel();
        $channel->send($user, $notification);
    }
}
