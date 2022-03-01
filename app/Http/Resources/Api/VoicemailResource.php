<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class VoicemailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'number_id' => $this->number_id,
            'contact' => $this->contact_id ? (new ContactResource($this->contact)) : null,
            'from' => $this->from,
            'media_url' => $this->media_url,
            'length' => $this->length,
            'transcription' => $this->transcription,
            'created_at' => $this->created_at->timezone($this->user->time_zone)->format(\DateTime::ISO8601),
        ];
    }
}
