<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\IngestProviderMessageJob;
use Illuminate\Http\Request;

class MessageIngestionController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        IngestProviderMessageJob::dispatch();
    }
}
