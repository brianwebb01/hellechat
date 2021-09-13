<?php

namespace App\Http\Controllers\Services\Twilio;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessTwilioVoicemail;
use App\Models\Number;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Twilio\TwiML\MessagingResponse;
use Twilio\TwiML\VoiceResponse;

class VoicemailController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function connect(Request $request, $numberHashId)
    {
        try {
            $number = Number::findByHashId($numberHashId);
        } catch (ModelNotFoundException) {
            return response('Number not found', 404);
        }

        $response = new VoiceResponse();
        $dial = $response->dial(null, [
            'answerOnBridge' => true,
            'timeout' => 30,
            'action' => route(
                'webhooks.twilio.voice.greeting',
                ['userHashId' => $request->route('userHashId')]
            )
        ]);
        $dial->sip($number->sip_registration_url);

        return response($response)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function greeting(Request $request)
    {
        $response = new VoiceResponse();
        $response->say('Please leave a message after the tone');
        $response->record([
            'transcribeCallback' => route(
                'webhooks.twilio.voice.store',[
                    'userHashId' => $request->route('userHashId')
                ]),
            'playBeep' => true,
            'maxLength' => 120
        ]);

        return response($response)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ProcessTwilioVoicemail::dispatch($request->all());

        return response(new MessagingResponse)
            ->header('Content-Type', 'text/xml');
    }
}
