<?php

namespace App\Notifications;

use App\Channels\GotifyChannel;
use App\Http\Resources\Api\MessageResource;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Illuminate\Notifications\Messages\BroadcastMessage;


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
        $channels = ['broadcast'];

        if($notifiable->gotify_app_token)
            $channels[] = GotifyChannel::class;

        return $channels;
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => new MessageResource($this->message)
        ]);
    }

    /**
     * Get the gotify representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \App\Models\Message
     */
    public function toGotify($notifiable)
    {
        if (!$this->message->body & count($this->message->media) > 0) {
            $msg = "Attachment";
        } else {
            $msg = $this->message->body;
        }

        if ($this->message->contact) {
            $title = "SMS from " . $this->message->contact->friendlyName();
        } else {
            $title = "SMS from " . $this->message->from;
        }

        $url = route('ui.thread.index', [
            'numberPhone' => $this->message->number->phone_number,
            'with' => $this->message->from
        ]);

        return [
            'title' => $title,
            'message' => $msg,
            'url' => $url
        ];
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
