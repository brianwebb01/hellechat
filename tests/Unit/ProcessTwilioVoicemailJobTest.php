<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Jobs\ProcessTwilioVoicemailJob;
use App\Models\Contact;
use App\Models\Number;
use App\Models\ServiceAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Mockery\Mock;
use Mockery\MockInterface;
use Twilio\Rest\Client;
use Twilio\Rest\Api\V2010\Account\RecordingContext;
use Twilio\Rest\Api\V2010\Account\RecordingInstance;

class ProcessTwilioVoicemailJobTest extends TestCase
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
    public function job_saves_voicemail_as_expected()
    {
        $user = User::factory()->create();
        $number = Number::factory()->create([
            'user_id' => $user->id,
        ]);
        $contact = Contact::factory()->create([
            'user_id' => $user->id,
            'phone_numbers' => ['mobile' => $this->faker->e164PhoneNumber]
        ]);
        $recordingSid = $this->faker->regexify('[A-Za-z0-9]{15}');

        $data = [
            'From' => $contact->phone_numbers['mobile'],
            'To' => $number->phone_number,
            'TranscriptionText' => 'howdy partner',
            'RecordingUrl' => $this->faker->imageUrl,
            'RecordingSid' => $recordingSid
        ];

        $mClient = Mockery::mock(Client::class, fn (MockInterface $mock) =>
            $mock->shouldReceive('recordings')
                ->once()
                ->with($recordingSid)
                ->andReturn(
                    Mockery::mock(RecordingContext::class, fn (MockInterface $mRecordingContext) =>
                        $mRecordingContext->shouldReceive('fetch')
                            ->once()
                            ->andReturn(
                                Mockery::mock(RecordingInstance::class, function (MockInterface $mRecordingInstance) {
                                    return $mRecordingInstance->duration = 12;
                                })
                            )
                    )
                )
        );
        $mServiceAccount = Mockery::mock(ServiceAccount::class, fn(MockInterface $mock) =>
            $mock->shouldReceive('getProviderClient')
                ->andReturn($mClient)
        );
        $number->serviceAccount = $mServiceAccount;

        $job = new ProcessTwilioVoicemailJob($number, $data);
        $job->handle();

        $voicemail = $user->voicemails()
            ->where('external_identity', $recordingSid)
            ->first();

        $this->assertNotNull($voicemail);
        $this->assertEquals($number->id, $voicemail->number_id);
        $this->assertEquals($contact->id, $voicemail->contact_id);
        $this->assertEquals($contact->phone_numbers['mobile'], $voicemail->from);
        $this->assertEquals($data['RecordingUrl'], $voicemail->media_url);
        $this->assertEquals($data['TranscriptionText'], $voicemail->transcription);
        $this->assertEquals(12, $voicemail->length);
    }
}
