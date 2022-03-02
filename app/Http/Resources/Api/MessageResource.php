<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'service_account_id' => $this->service_account_id,
            'contact' => $this->contact_id ? (new ContactResource($this->contact)) : null,
            'from' => $this->from,
            'to' => $this->to,
            'body' => $this->body,
            'error_code' => $this->error_code,
            'error_message' => $this->error_message,
            'direction' => $this->direction,
            'status' => $this->status,
            'num_media' => $this->num_media,
            'media' => $this->media,
            'external_identity' => $this->external_identity,
            'created_at' => $this->created_at->timezone($this->user->time_zone)->format(\DateTime::ISO8601),
            'read' => $this->read,
        ];
    }
}
