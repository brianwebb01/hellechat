<?php

namespace App\Http\Controllers\Services\Twilio;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessTwilioVoicemailJob;
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

        $voicemailGreetingUrl = route(
            'webhooks.twilio.voice.greeting',
            ['userHashId' => $request->route('userHashId')]
        );

        $contact = $number->user->contacts()
            ->firstWhere('phone_numbers', 'like', '%' . $request->get('From') . '%');

        $response = new VoiceResponse();

        if($number->shouldRing($contact)){

            $dial = $response->dial(null, [
                'timeout' => 10,
                'ringTone' => 'us',
                'action' => $voicemailGreetingUrl
            ]);
            $dial->sip($number->sip_registration_url);

        } else {
            $response->redirect($voicemailGreetingUrl);
        }


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

        if(\in_array($request->get('DialCallStatus'), ['completed', 'answered'])){
            $response->hangup();
            return response($response)
                ->header('Content-Type', 'text/xml');
        }

        $response->say(
            'The party you have called is unavailable. Please leave a message after the tone.'
        );
        $response->pause(['length' => 1]);
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
        $number = Number::wherePhoneNumber($request->get('To'))
            ->first();

        if (is_null($number)) {
            throw new ModelNotFoundException(
                "No number record found for " . $request->get('To')
            );
        }

        ProcessTwilioVoicemailJob::dispatch($number, $request->all());

        return response(new MessagingResponse)
            ->header('Content-Type', 'text/xml');
    }
}
