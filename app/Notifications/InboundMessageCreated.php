<?php

namespace App\Notifications;

use App\Channels\GotifyChannel;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class InboundMessageCreated extends Notification
{
    use Queueable;

    public Message $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = [];

        if($notifiable->gotify_app_token)
            $channels[] = GotifyChannel::class;

        return $channels;
    }

    /**
     * Get the gotify representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \App\Models\Message
     */
    public function toGotify($notifiable)
    {
        return $this->message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message->toArray()
        ];
    }
}