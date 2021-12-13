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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ThreadController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $IDsAndReads = Thread::getRecentThreadSql($request->user());

        $response = $request->user()->messages()
            ->with('number', 'contact')
            ->whereIn('id', collect($IDsAndReads)->pluck('id')->flatten())
            ->orderBy('created_at', 'desc')
            ->paginate();

        $array = $response->toArray();
        $array = Thread::addReadCountsForRecentThreads($array, $IDsAndReads);
        return Thread::formatApiResponse($array);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $phoneNumber
     * @return array
     */
    public function show(Request $request, $phoneNumber)
    {
        $messages = $request->user()->messages()
            ->where('from', $phoneNumber)
            ->orWhere('to', $phoneNumber)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return new MessageCollection($messages);
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
