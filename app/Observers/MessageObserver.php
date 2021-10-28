<?php

namespace App\Observers;

use App\Jobs\ProcessOutboundTwilioMessageJob;
use App\Models\Message;
use App\Models\ServiceAccount;
use Illuminate\Support\Facades\Log;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     *
     * @param  \App\Models\Message  $message
     * @return void
     */
    public function created(Message $message)
    {
        if($message->direction == Message::DIRECTION_OUT){
            if($message->number->serviceAccount->provider == ServiceAccount::PROVIDER_TWILIO){
                ProcessOutboundTwilioMessageJob::dispatch(
                    $message->number->serviceAccount, $message
                );
            }
        }
    }

    /**
     * Handle the Message "updated" event.
     *
     * @param  \App\Models\Message  $message
     * @return void
     */
    public function updated(Message $message)
    {
        //
    }

    /**
     * Handle the Message "deleted" event.
     *
     * @param  \App\Models\Message  $message
     * @return void
     */
    public function deleted(Message $message)
    {
        $message->deleteMediaFiles();
    }

    /**
     * Handle the Message "restored" event.
     *
     * @param  \App\Models\Message  $message
     * @return void
     */
    public function restored(Message $message)
    {
        //
    }

    /**
     * Handle the Message "force deleted" event.
     *
     * @param  \App\Models\Message  $message
     * @return void
     */
    public function forceDeleted(Message $message)
    {
        //
    }
}
