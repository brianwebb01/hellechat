<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\MessageCollection;
use App\Models\Message;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\Api\MessageCollection
     */
    public function index(Request $request)
    {
        $messages = $request->user()->messages()->paginate();

        return new MessageCollection($messages);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $phoneNumber)
    {
        $request->user()->messages()
            ->where('from', $phoneNumber)
            ->orWhere('to', $phoneNumber)
            ->delete();

        return response()->noContent();
    }
}
