<?php

namespace Tests\Feature\Notifications;

use App\Channels\GotifyChannel;
use App\Models\Contact;
use App\Models\Voicemail;
use App\Notifications\VoicemailCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoicemailCreatedTest extends TestCase
{
    /**
     * @test
     */
    public function gotify_channel_missing_as_expected()
    {
        $voicemail = Voicemail::factory()->create();

        //default user factory has no 'gotify_app_token'
        $user = $voicemail->user;

        $notification = new VoicemailCreated($voicemail);
        $this->assertFalse(in_array(GotifyChannel::class, $notification->via($user)));
    }

    /**
     * @test
     */
    public function notification_functions_as_expected_w_contact()
    {
        $contact = Contact::factory()->create();
        $voicemail = Voicemail::factory()->create([
            'contact_id' => $contact->id
        ]);
        $user = $voicemail->user;
        $user->gotify_app_token = 'abc123';
        $user->save();

        $notification = new VoicemailCreated($voicemail);
        $this->assertEquals(['voicemail' => $voicemail->toArray()], $notification->toArray($user));
        $this->assertTrue(in_array(GotifyChannel::class, $notification->via($user)));

        $data = $notification->toGotify($user);
        $this->assertEquals("Voicemail from " . $contact->friendlyName(), $data['title']);
        $this->assertEquals($voicemail->transcription, $data['message']);
        $this->assertEquals(route('ui.thread.index', [
            'numberPhone' => $voicemail->number->phone_number,
            'with' => $voicemail->from
        ]), $data['url']);
    }

    /**
     * @test
     */
    public function notification_functions_as_expected_w_no_contact()
    {
        $voicemail = Voicemail::factory()->create([
            'contact_id' => null
        ]);
        $user = $voicemail->user;
        $user->gotify_app_token = 'abc123';
        $user->save();

        $notification = new VoicemailCreated($voicemail);
        $this->assertEquals(['voicemail' => $voicemail->toArray()], $notification->toArray($user));
        $this->assertTrue(in_array(GotifyChannel::class, $notification->via($user)));

        $data = $notification->toGotify($user);
        $this->assertEquals("Voicemail from " . $voicemail->from, $data['title']);
        $this->assertEquals($voicemail->transcription, $data['message']);
        $this->assertEquals(route('ui.thread.index', [
            'numberPhone' => $voicemail->number->phone_number,
            'with' => $voicemail->from
        ]), $data['url']);
    }
}
