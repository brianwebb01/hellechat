<?php

namespace App\Jobs;

use App\Models\Number;
use App\Models\Voicemail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class ProcessTwilioVoicemail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $input;
    private $number;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($input)
    {
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->number = Number::wherePhoneNumber($this->input['To'])
            ->first();

        if (is_null($this->number)) {
            Log::error("No number record found for " . $this->intput['To']);
            return;
        }

        $data = [
            'from' => $this->input['From'],
            'media_url' => $this->input['RecordingUrl'],
            'length' => $this->getRecordingDuration(),
            'transcription' => $this->input['TranscriptionText'],
            'external_identity' => $this->input['RecordingSid']
        ];

        $voicemail = new Voicemail($data);
        $voicemail->user_id = $this->number->user_id;
        $voicemail->number_id = $this->number->id;
        $voicemail->contact_id = $this->getContactId();
        $voicemail->save();
    }

    /**
     * Query the contacts of the user who owns the Number
     * and return the contact's id if any.
     *
     * @return integer|null
     */
    private function getContactId()
    {
        $contact = $this->number->user->contacts()
            ->firstWhere('phone_numbers', 'like', '%' . $this->input['From'] . '%');

        return $contact ? $contact->id : null;
    }

    /**
     * Make an api request to twilio to get the duration
     * of the recording using the ID given in the callback
     * input
     *
     * @return integer
     */
    private function getRecordingDuration()
    {
        try {
            $tw = app(\Twilio\Rest\Client::class);

            $recording = $tw->recordings($this->input['RecordingSid'])
                ->fetch();

            return $recording->duration;

        } catch(\Twilio\Exceptions\RestException $e){
            return 0;
        }
    }
}
