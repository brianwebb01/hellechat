<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\IngestProviderVoicemailJob;
use Illuminate\Http\Request;

class VoicemailIngestionController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        IngestProviderVoicemailJob::dispatch();
    }
}
