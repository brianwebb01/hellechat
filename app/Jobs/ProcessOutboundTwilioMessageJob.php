<?php

namespace App\Jobs;

use App\Models\Message;
use App\Models\ServiceAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class ProcessOutboundTwilioMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $serviceAccount;
    public $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ServiceAccount $serviceAccount, Message $message)
    {
        $this->serviceAccount = $serviceAccount;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tw = $this->serviceAccount->getProviderClient();

        $callbackUrl = route(
            'webhooks.twilio.messaging.status',
            ['userHashId' => $this->message->user->getHashId()]
        );

        $data = [
            'from' => $this->message->from,
            'statusCallback' => $callbackUrl
        ];

        if($this->message->body)
            $data['body'] = $this->message->body;

        if(! empty($this->message->media))
            $data['mediaUrl'] = $this->message->media;

        if(config('services.twilio.use_sandbox') === true){
            $remoteMessage = \json_decode(\json_encode([
                'sid' => \base64_encode(\uniqid('', true)),
                'status' => Message::STATUS_DELIVERED
            ]));
        } else {
            $remoteMessage = $tw->messages->create(
                $this->message->to,
                $data
            );
        }

        $this->message->update([
            'external_identity' => $remoteMessage->sid,
            'status' => $remoteMessage->status
        ]);
    }
}
