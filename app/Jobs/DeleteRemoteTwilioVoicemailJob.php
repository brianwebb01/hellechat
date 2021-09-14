<?php

namespace App\Jobs;

use App\Models\ServiceAccount;
use App\Models\Voicemail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteRemoteTwilioVoicemailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $serviceAccount;
    public $voicemail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ServiceAccount $serviceAccount, Voicemail $voicemail)
    {
        $this->serviceAccount = $serviceAccount;
        $this->voicemail = $voicemail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tw = $this->serviceAccount->getProviderClient();
        $tw->recordings($this->voicemail->external_identity)->delete();
    }
}
