<?php

namespace App\Channels;

use App\Services\Gotify\Client;
use Illuminate\Notifications\Notification;

class GotifyChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toGotify($notifiable);

        $gotify = Client::createWithTokenAuth(
            $notifiable->gotify_app_token,
            config('services.gotify.url')
        );

        $gotify->createMessage(
            $data['title'],
            $data['message'],
            $data['url']
        );
    }
}
