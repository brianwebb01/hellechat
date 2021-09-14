<?php

namespace Tests\Unit;

use App\Jobs\ProcessOutboundTwilioMessageJob;
use App\Models\Message;
use App\Models\ServiceAccount;
use App\Models\Number;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class MessageTest extends TestCase
{
    /**
     * @test
     */
    public function dispatches_remote_provider_job_as_expected()
    {
        $user = User::factory()->create();
        $serviceAccount = ServiceAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => ServiceAccount::PROVIDER_TWILIO
        ]);
        $number = Number::factory()->create([
            'user_id' => $user->id,
            'service_account_id' => $serviceAccount->id
        ]);
        $message = Message::factory()->make([
            'user_id' => $user->id,
            'number_id' => $number->id,
            'direction' => Message::DIRECTION_OUT,
        ]);

        Queue::fake();

        $message->save();

        Queue::assertPushed(ProcessOutboundTwilioMessageJob::class);
    }


}
