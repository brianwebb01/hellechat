<?php

namespace Tests\Unit;

use App\Jobs\ProcessOutboundTwilioMessageJob;
use App\Models\Message;
use App\Models\Number;
use App\Models\ServiceAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Api\V2010\Account\MessageList;
use Twilio\Rest\Client;

class ProcessOutboundTwilioMessageJobTest extends TestCase
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
    public function send_twilio_sms_behaves_as_expected()
    {
        $user = User::factory()->create();
        $serviceAccount = ServiceAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => ServiceAccount::PROVIDER_TWILIO
        ]);
        $number = Number::factory()->create([
            'user_id' => $user->id,
            'service_account_id' => $serviceAccount->id,
            'phone_number' => '+15024105645'
        ]);
        $to = '+15022987961';

        $media = [
            $this->faker->imageUrl(),
            $this->faker->imageUrl()
        ];

        $message = Message::factory()->make([
            'user_id' => $user->id,
            'number_id' => $number->id,
            'service_account_id' => $serviceAccount->id,
            'from' => $number->phone_number,
            'to' => $to,
            'body' => now()->timezone('America/New_York')->toDateTimeString(),
            'media' => $media,
            'num_media' => count($media),
            'direction' => Message::DIRECTION_OUT,
            'status' => Message::STATUS_LOCAL_CREATED,
            'external_identity' => null
        ]);

        $message->saveQuietly();
        $this->assertNull($message->external_identity);

        $mClient = Mockery::mock(Client::class, function (MockInterface $mock) use ($message) {
            $mock->messages = Mockery::mock(
                MessageList::class,
                fn (MockInterface $mMessageList) =>
                $mMessageList->shouldReceive('create')
                    ->once()
                    ->with(
                        $message->to,
                        [
                            'from' => $message->from,
                            'statusCallback' => route(
                                'webhooks.twilio.messaging.status',
                                ['userHashId' => $message->user->getHashId()]
                            ),
                            'body' => $message->body,
                            'mediaUrl' => $message->media
                        ]
                    )
                    ->andReturn(
                        Mockery::mock(MessageInstance::class, function (MockInterface $mMessageInstance) {
                            $mMessageInstance->sid = 'abc123sid';
                            $mMessageInstance->status = Message::STATUS_SENT;
                            return $mMessageInstance;
                        })
                    )
            );
            return $mock;
        });

        $mServiceAccount = Mockery::mock(
            ServiceAccount::class,
            fn (MockInterface $mock) =>
            $mock->shouldReceive('getProviderClient')
            ->andReturn($mClient)
        );

        $job = new ProcessOutboundTwilioMessageJob(
            $mServiceAccount,
            $message
        );
        $job->handle();

        $message->fresh();
        $this->assertEquals('abc123sid', $message->external_identity);
        $this->assertEquals(Message::STATUS_SENT, $message->status);
    }
}
