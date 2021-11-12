<?php

namespace App\Observers;

use App\Jobs\ConnectContactToMessagesJob;
use App\Jobs\ConnectContactToVoicemailsJob;
use App\Jobs\DisconnectContactFromMessagesJob;
use App\Jobs\DisconnectContactFromVoicemailsJob;
use App\Models\Contact;

class ContactObserver
{
    /**
     * Handle the Contact "created" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function created(Contact $contact)
    {
        ConnectContactToMessagesJob::dispatch($contact);
        ConnectContactToVoicemailsJob::dispatch($contact);
    }

    /**
     * Handle the Contact "updated" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function updated(Contact $contact)
    {
        ConnectContactToMessagesJob::dispatch($contact);
        ConnectContactToVoicemailsJob::dispatch($contact);
    }

    /**
     * Handle the Contact "deleted" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function deleted(Contact $contact)
    {
        DisconnectContactFromMessagesJob::dispatch($contact->id);
        DisconnectContactFromVoicemailsJob::dispatch($contact->id);
    }

    /**
     * Handle the Contact "restored" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function restored(Contact $contact)
    {
        ConnectContactToMessagesJob::dispatch($contact);
        ConnectContactToVoicemailsJob::dispatch($contact);
    }

    /**
     * Handle the Contact "force deleted" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function forceDeleted(Contact $contact)
    {
        DisconnectContactFromMessagesJob::dispatch($contact->id);
        DisconnectContactFromVoicemailsJob::dispatch($contact->id);
    }
}
