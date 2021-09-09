<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\MessageCollection;
use App\Http\Resources\ThreadCollection;
use App\Http\Resources\ThreadResource;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Utils\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $threads = Thread::threadsSummaryForUser($request->user());

        return ['data' => $threads];
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $phoneNumber
     * @return array
     */
    public function show(Request $request, $phoneNumber)
    {
        $thread = Thread::getThread($request->user(), $phoneNumber);

        return ['data' => $thread];
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $phoneNumber
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
