<?php

namespace App\Jobs;

use App\Models\Message;
use App\Models\Number;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessInboundTwilioMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $input;

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
        $number = Number::wherePhoneNumber($this->input['To'])
            ->first();

        if (is_null($number)) {
            Log::error('No number record found for '.$this->intput['To']);

            return;
        }

        $data = [
            'from' => $this->input['From'],
            'to' => $this->input['To'],
            'body' => $this->input['Body'],
            'direction' => Message::DIRECTION_IN,
            'status' => $this->input['SmsStatus'],
            'num_media' => $this->input['NumMedia'],
            'media' => $this->getMediaArray(),
            'external_identity' => $this->input['MessageSid'],
        ];

        $contact = $number->user->contacts()
            ->firstWhere('phone_numbers', 'like', '%'.$this->input['From'].'%');

        $message = new Message($data);
        $message->number_id = $number->id;
        $message->user_id = $number->user_id;
        $message->service_account_id = $number->service_account_id;
        $message->contact_id = $contact ? $contact->id : null;
        $message->save();
    }

    /**
     * Function to return an array of media URLs
     *
     * @return array
     */
    private function getMediaArray()
    {
        $media = [];

        $types = [
            'png' => 'image/png',
            'jpg' => 'image/jpg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif'
        ];

        if ($this->input['NumMedia'] > 0) {
            foreach (range(0, $this->input['NumMedia'] - 1) as $i) {
                $url = $this->input["MediaUrl{$i}"];
                echo $url."\n";
                if(preg_match("/.*\.([png|jpg|jpeg|gif]*).*/i", $url, $matches)){
                    if($matches[1] != ""){
                        $contentTypeStr = $types[$matches[1]];
                    } else {
                        $headers = get_headers($url, true);
                        $contentTypeStr = $headers['Content-Type'];
                    }
                    $url .= (\stripos($url, '?') == false ? '?' : '&').'Content-Type='.$contentTypeStr;
                }

                $media[] = $url;
            }
        }

        return $media;
    }
}
