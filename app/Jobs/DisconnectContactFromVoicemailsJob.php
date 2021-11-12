<?php

namespace App\Jobs;

use App\Models\Voicemail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DisconnectContactFromVoicemailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $contactId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($contactId)
    {
        $this->contactId = $contactId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Voicemail::query()
            ->where('contact_id', $this->contactId)
            ->update(['contact_id' => null]);
    }
}
