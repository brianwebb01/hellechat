<?php

namespace Tests\Unit;

use App\Jobs\DeleteRemoteTwilioVoicemail;
use App\Models\Number;
use App\Models\ServiceAccount;
use App\Models\Voicemail;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Mockery\MockInterface;

class DeleteRemoteTwilioVoicemailTest extends TestCase
{
    /**
     * @test
     */
    public function delete_job_works_as_expected()
    {
        $recordingSid = 'abc123';
        $voicemail = Voicemail::factory()->create([
            'external_identity' => $recordingSid
        ]);

        $mServiceAccount = Mockery::mock(ServiceAccount::class, fn(MockInterface $saMock) =>
            $saMock->shouldReceive('getProviderClient')
                ->once()
                ->andReturn(
                    Mockery::mock(Client::class, fn (MockInterface $mock) =>
                        $mock->shouldReceive('recordings')
                        ->once()
                        ->with($recordingSid)
                        ->andReturn(
                            Mockery::mock(RecordingContext::class, fn (MockInterface $mRecordingContext) =>
                                $mRecordingContext->shouldReceive('delete')->once()
                            )
                        )
                    )
                )
        );

        $job = new DeleteRemoteTwilioVoicemail($mServiceAccount, $voicemail);
        $job->handle();
    }

    /**
     * @test
     */
    public function model_observer_works_as_expected()
    {
        $serviceAccount = ServiceAccount::factory()->create([
            'provider' => ServiceAccount::PROVIDER_TWILIO
        ]);
        $number = Number::factory()->create([
            'service_account_id' => $serviceAccount->id,
        ]);
        $recordingSid = 'abc123';
        $voicemail = Voicemail::factory()->create([
            'number_id' => $number->id,
            'external_identity' => $recordingSid
        ]);

        Queue::fake();

        $voicemail->delete();

        Queue::assertPushed(DeleteRemoteTwilioVoicemail::class);
    }
}
