<?php

namespace App\Jobs;

use App\Models\ServiceAccount;
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

    public $provider_identity;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ServiceAccount $serviceAccount, $provider_identity)
    {
        $this->serviceAccount = $serviceAccount;
        $this->provider_identity = $provider_identity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tw = $this->serviceAccount->getProviderClient();
        $tw->recordings($this->provider_identity)->delete();
    }
}
