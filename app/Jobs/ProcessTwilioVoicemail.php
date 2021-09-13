<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTwilioVoicemail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $input;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($input)
    {
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /*
        ApiVersion=2010-04-01
        TranscriptionType=fast
        TranscriptionUrl=https%3A%2F%2Fapi.twilio.com%2F2010-04-01%2FAccounts%2FAC3f1496421dea2c8446672cdf90ff4c7a%2FRecordings%2FRE2b568e31f38645c9c5c3ed190a0da06a%2FTranscriptions%2FTRd16d9b52364b51a7e7b3cb7458017463
        TranscriptionSid=TRd16d9b52364b51a7e7b3cb7458017463
        Called=%2B15024105645
        RecordingSid=RE2b568e31f38645c9c5c3ed190a0da06a
        CallStatus=completed
        RecordingUrl=https%3A%2F%2Fapi.twilio.com%2F2010-04-01%2FAccounts%2FAC3f1496421dea2c8446672cdf90ff4c7a%2FRecordings%2FRE2b568e31f38645c9c5c3ed190a0da06a
        From=%2B14708231590
        Direction=inbound
        url=http%3A%2F%2Fbwebb.ngrok.io%2Fwebhooks%2Ftwilio%2Fvoicemail%2Fstore%2FRGe8jL
        AccountSid=AC3f1496421dea2c8446672cdf90ff4c7a
        TranscriptionText=123123%20test.
        Caller=%2B14708231590
        TranscriptionStatus=completed
        CallSid=CAbea03a48a7be37048368488684116087
        To=%2B15024105645
        ForwardedFrom=%2B15024105645
        */
    }
}
