<?php

namespace Tests\Feature\Notifications;

use App\Channels\GotifyChannel;
use App\Models\Contact;
use App\Models\Message;
use App\Notifications\InboundMessageCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InboundMessageCreatedTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    /**
     * @test
     */
    public function notification_functions_as_expected_w_contact_no_attachment()
    {
        $contact = Contact::factory()->create();
        $message = Message::factory()->create([
            'contact_id' => $contact->id,
            'direction' => Message::DIRECTION_IN,
            'media' => [],
            'num_media' => 0,
        ]);
        $user = $message->user;
        $user->gotify_app_token = 'abc123';
        $user->save();

        $notification = new InboundMessageCreated($message);
        $this->assertEquals(['message' => $message->toArray()], $notification->toArray($user));
        $this->assertTrue(in_array(GotifyChannel::class, $notification->via($user)));

        $data = $notification->toGotify($user);
        $this->assertEquals('SMS from '.$contact->friendlyName(), $data['title']);
        $this->assertEquals($message->body, $data['message']);
        $this->assertEquals(route('ui.thread.index', [
            'numberPhone' => $message->number->phone_number,
            'with' => $message->from,
        ]), $data['url']);
    }

    /**
     * @test
     */
    public function notification_functions_as_expected_w_attachment()
    {
        $message = Message::factory()->create([
            'contact_id' => null,
            'direction' => Message::DIRECTION_IN,
            'media' => [$this->faker->imageUrl()],
            'num_media' => 1,
            'body' => null,
        ]);
        $user = $message->user;
        $user->gotify_app_token = 'abc123';
        $user->save();

        $notification = new InboundMessageCreated($message);
        $this->assertEquals(['message' => $message->toArray()], $notification->toArray($user));
        $this->assertTrue(in_array(GotifyChannel::class, $notification->via($user)));

        $data = $notification->toGotify($user);
        $this->assertEquals('SMS from '.$message->from, $data['title']);
        $this->assertEquals('Attachment', $data['message']);
        $this->assertEquals(route('ui.thread.index', [
            'numberPhone' => $message->number->phone_number,
            'with' => $message->from,
        ]), $data['url']);
    }

    /**
     * @test
     */
    public function gotify_channel_missing_as_expected()
    {
        $message = Message::factory()->create([
            'direction' => Message::DIRECTION_IN,
        ]);

        //default user factory has no 'gotify_app_token'
        $user = $message->user;

        $notification = new InboundMessageCreated($message);
        $this->assertFalse(in_array(GotifyChannel::class, $notification->via($user)));
    }
}
