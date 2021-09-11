<?php

namespace App\Http\Controllers\Services\Twilio;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessInboundTwilioMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MessagingController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $userHashId)
    {

        ProcessInboundTwilioMessage::dispatch();
    }
}
