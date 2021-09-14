<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MessageStoreRequest;
use App\Http\Resources\Api\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * @param \App\Http\Requests\Api\MessageStoreRequest $request
     * @return \App\Http\Resources\Api\MessageResource
     */
    public function store(MessageStoreRequest $request)
    {
        $message = new Message($request->validated());

        $number = $request->user()
            ->numbers()
            ->where('phone_number', $request->get('from'))
            ->firstOrFail();

        $contact = $request->user()
            ->contacts()
            ->where('phone_numbers', 'like', '%'.$request->get('to').'%')
            ->first();

        $message->contact_id = $contact ? $contact->id : null;
        $message->number_id = $number->id;
        $message->service_account_id = $number->serviceAccount->id;

        $message = $request->user()
            ->messages()
            ->save($message);

        return new MessageResource($message);
    }
}
