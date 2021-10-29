<?php

namespace Tests\Unit;

use App\Channels\GotifyChannel;
use App\Jobs\ProcessOutboundTwilioMessageJob;
use App\Models\Message;
use App\Models\ServiceAccount;
use App\Models\Number;
use App\Models\User;
use App\Notifications\InboundMessageCreated;
use App\Services\Gotify\Client;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
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



    /** @test */
    public function notifies_user_of_new_inbound_message_as_expected()
    {

        $user = User::factory()->create([
            'gotify_app_token' => 'abc123'
        ]);
        $message = Message::factory()->make([
            'user_id' => $user->id,
            'direction' => Message::DIRECTION_IN,
            'media' => [],
            'num_media' => 0,
            'body' => 'foobar',
            'contact_id' => null
        ]);


        $mGotify = \Mockery::mock(
            Client::class,
            fn (MockInterface $mock) =>
            $mock->shouldReceive('createMessage')
                ->with(
                    "SMS from ". $message->from,
                    $message->body,
                    route('ui.thread.index', [
                        'numberPhone' => $message->number->phone_number,
                        'with' => $message->from
                    ])
                )
        );
        $this->app->instance(Client::class, $mGotify);


        $message->save();

    }


}
