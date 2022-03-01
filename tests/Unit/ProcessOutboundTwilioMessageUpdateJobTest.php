<?php

namespace Tests\Unit;

use App\Jobs\ProcessOutboundTwilioMessageUpdateJob;
use App\Models\Message;
use App\Models\User;
use Tests\TestCase;

class ProcessOutboundTwilioMessageUpdateJobTest extends TestCase
{
    /**
     * @test
     */
    public function updates_message_status_as_expected()
    {
        $message = Message::factory()->make([
            'status' => Message::STATUS_LOCAL_CREATED,
        ]);
        $message->saveQuietly();

        $job = new ProcessOutboundTwilioMessageUpdateJob([
            'MessageSid' => $message->external_identity,
            'MessageStatus' => Message::STATUS_DELIVERED,
        ]);
        $job->handle();

        $message = $message->fresh();
        $this->assertEquals(Message::STATUS_DELIVERED, $message->status);
    }
}
