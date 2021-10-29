<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\Message;
use App\Models\User;
use App\Models\Voicemail;
use App\Observers\ContactObserver;
use App\Observers\MessageObserver;
use App\Observers\UserObserver;
use App\Observers\VoicemailObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Voicemail::observe(VoicemailObserver::class);
        Message::observe(MessageObserver::class);
        Contact::observe(ContactObserver::class);
        User::observe(UserObserver::class);
    }
}
