<?php

namespace Tests\Feature\Http\Controllers\Services\Twilio;

use App\Jobs\ProcessInboundTwilioMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Services\Twilio\MessagingController
 */
class MessagingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function store_behaves_as_expected()
    {
        $user = User::factory()->create();

        Queue::fake();

        $response = $this->post(route('webhooks.twilio.messaging', ['userHashId' => $user->getHashId()]));
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<Response/>\n";
        $this->assertEquals($xml, $response->getContent());

        Queue::assertPushed(ProcessInboundTwilioMessage::class);
    }

    /**
     * @test
     */
    public function store_fails_with_invalid_user()
    {
        $response = $this->post(route('webhooks.twilio.messaging', ['userHashId' => 'invalid']));
        $response->assertForbidden();
    }
}
