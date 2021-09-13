<?php

namespace Tests\Feature\Http\Controllers\Services\Twilio;

use App\Jobs\ProcessTwilioVoicemail;
use App\Models\Number;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Services\Twilio\VoicemailController
 */
class VoicemailControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $number;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->number = Number::factory()->create(['user_id' => $this->user->id]);
    }

    /**
     * @test
     */
    public function connect_behaves_as_expected()
    {
        $response = $this->post(route('webhooks.twilio.voice', [
            'numberHashId' => $this->number->getHashId(),
            'userHashId' => $this->user->getHashId()
        ]));

        $response->assertOk();
    }


    /**
     * @test
     */
    public function greeting_behaves_as_expected()
    {
        $response = $this->post(route('webhooks.twilio.voice.greeting', [
            'userHashId' => $this->user->getHashId()
        ]));

        $response->assertOk();
    }


    /**
     * @test
     */
    public function store_behaves_as_expected()
    {
        Queue::fake();

        $response = $this->post(route('webhooks.twilio.voice.store', [
            'userHashId' => $this->user->getHashId()
        ]));

        Queue::assertPushed(ProcessTwilioVoicemail::class);
    }
}
