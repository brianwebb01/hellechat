<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ImportContactsJob;
use Illuminate\Http\Request;

class ContactImportController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ImportContactsJob::dispatch();
    }
}
