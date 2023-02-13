<?php

namespace App\Notifications;

use App\Channels\GotifyChannel;
use App\Models\Voicemail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VoicemailCreated extends Notification
{
    use Queueable;

    public Voicemail $voicemail;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Voicemail $voicemail)
    {
        $this->voicemail = $voicemail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = ['mail'];

        if ($notifiable->gotify_app_token) {
            $channels[] = GotifyChannel::class;
        }

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
        if ($this->voicemail->contact) {
            $title = 'Voicemail from '.$this->voicemail->contact->friendlyName();
        } else {
            $title = 'Voicemail from '.$this->voicemail->from;
        }

        $url = route('ui.thread.index', [
            'numberPhone' => $this->voicemail->number->phone_number,
            'with' => $this->voicemail->from,
        ]);

        return [
            'title' => $title,
            'message' => $this->voicemail->transcription,
            'url' => $url,
        ];
    }

    public function toMail($notifiable)
    {
        if ($this->voicemail->contact) {
            $title = 'Voicemail from ' . $this->voicemail->contact->friendlyName();
        } else {
            $title = 'Voicemail from ' . $this->voicemail->from;
        }

        $url = route('ui.thread.index', [
            'numberPhone' => $this->voicemail->number->phone_number,
            'with' => $this->voicemail->from,
        ]);

        $message = $this->voicemail->transcription;

        return (new MailMessage)
            ->subject($title)
            ->greeting($title)
            ->line($message)
            ->action('View Voicemail', $url);
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
            'voicemail' => $this->voicemail->toArray(),
        ];
    }
}
