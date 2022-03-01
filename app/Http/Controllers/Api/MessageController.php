<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MessageStoreRequest;
use App\Http\Requests\Api\MessageUpdateRequest;
use App\Http\Resources\Api\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        if ($request->hasFile('media')) {
            $dir = 'messages/'.$request->user()->id;
            if (! Storage::exists($dir)) {
                Storage::makeDirectory($dir);
            }
            foreach ($request->file('media') as $file) {
                $paths[] = Storage::url($file->storePublicly($dir, 'public'));
            }
            $message->media = $paths;
        }

        $message = $request->user()
            ->messages()
            ->save($message);

        return new MessageResource($message);
    }

    /**
     * @param \App\Http\Requests\Api\MessageUpdateRequest $request
     * @param \App\Models\Message $message
     * @return \App\Http\Resources\Api\MessageResource
     */
    public function update(MessageUpdateRequest $request, Message $message)
    {
        $message->update([
            'read' => $request->get('read'),
        ]);

        return new MessageResource($message);
    }
}
