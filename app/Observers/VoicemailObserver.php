<?php

namespace App\Observers;

use App\Jobs\DeleteRemoteTwilioVoicemailJob;
use App\Models\ServiceAccount;
use App\Models\Voicemail;
use App\Notifications\VoicemailCreated;

class VoicemailObserver
{
    /**
     * Handle the Voicemail "created" event.
     *
     * @param  \App\Models\Voicemail  $voicemail
     * @return void
     */
    public function created(Voicemail $voicemail)
    {
        $user = $voicemail->user;
        $user->notify(new VoicemailCreated($voicemail));
    }

    /**
     * Handle the Voicemail "updated" event.
     *
     * @param  \App\Models\Voicemail  $voicemail
     * @return void
     */
    public function updated(Voicemail $voicemail)
    {
        //
    }

    /**
     * Handle the Voicemail "deleted" event.
     *
     * @param  \App\Models\Voicemail  $voicemail
     * @return void
     */
    public function deleted(Voicemail $voicemail)
    {
        if($voicemail->number->serviceAccount->provider == ServiceAccount::PROVIDER_TWILIO){
            DeleteRemoteTwilioVoicemailJob::dispatch(
                $voicemail->number->serviceAccount,
                $voicemail->external_identity
            );
        }
    }

    /**
     * Handle the Voicemail "restored" event.
     *
     * @param  \App\Models\Voicemail  $voicemail
     * @return void
     */
    public function restored(Voicemail $voicemail)
    {
        //
    }

    /**
     * Handle the Voicemail "force deleted" event.
     *
     * @param  \App\Models\Voicemail  $voicemail
     * @return void
     */
    public function forceDeleted(Voicemail $voicemail)
    {
        //
    }
}
