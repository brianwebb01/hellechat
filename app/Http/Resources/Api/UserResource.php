<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $request->user();

        return [
            'id' => $user->id,
            'hash_id' => $user->getHashId(),
            'email' => $user->email,
            'twilio_messaging_endpoint' => route('webhooks.twilio.messaging', ['userHashId' => $user->getHashId()]),
            'twilio_voice_endpoints' => $user->numbers->map(
                fn ($number) => [
                    $number->phone_number => route('webhooks.twilio.voice', [
                        'userHashId' => $user->getHashId(),
                        'numberHashId' => $number->getHashId(),
                    ]),
                ]
            )->flatMap(fn ($a) => $a),
            'created_at' => $user->created_at->timezone($user->time_zone)->format(\DateTime::ISO8601),
        ];
    }
}
