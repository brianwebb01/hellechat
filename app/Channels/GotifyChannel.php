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
        $message = $notification->toGotify($notifiable);

        $gotify = Client::createWithTokenAuth(
            $notifiable->gotify_app_token,
            config('services.gotify.url')
        );

        if(!$message->body & count($message->media) > 0){
            $msg = "Attachment";
        } else {
            $msg = $message->body;
        }

        if($message->contact){
            $title = "SMS from ". $message->contact->friendlyName();
        } else {
            $title = "SMS from ". $message->from;
        }

        $gMessage = $gotify->createMessage(
            $title,
            $msg,
            route('ui.thread.index', [
                'numberPhone' => $message->number->phone_number,
                'with' => $message->from
            ])
        );
    }
}