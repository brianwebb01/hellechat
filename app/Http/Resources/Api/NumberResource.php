<?php

namespace App\Http\Resources\Api;

use App\Models\ServiceAccount;
use Illuminate\Http\Resources\Json\JsonResource;

class NumberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'service_account' => (new ServiceAccountResource($this->serviceAccount)),
            'service_account_id' => $this->service_account_id,
            'phone_number' => $this->phone_number,
            'friendly_label' => $this->friendly_label,
            'external_identity' => $this->external_identity,
            'sip_registration_url' => $this->sip_registration_url,
            'messaging_endpoint' => null,
            'voice_endpoint' => null,
            'dnd_calls' => $this->dnd_calls,
            'dnd_voicemail' => $this->dnd_voicemail,
            'dnd_messages' => $this->dnd_messages,
            'dnd_allow_contacts' => $this->dnd_allow_contacts
        ];

        if($this->serviceAccount->provider == ServiceAccount::PROVIDER_TWILIO){
            $data['messaging_endpoint'] = route('webhooks.twilio.messaging', [
                'userHashId' => $this->user->getHashId()
            ]);
            $data['voice_endpoint'] = route('webhooks.twilio.voice', [
                    'userHashId' => $this->user->getHashId(),
                    'numberHashId' => $this->getHashId()
            ]);
        }

        return $data;
    }
}
