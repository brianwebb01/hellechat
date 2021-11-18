<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactImportStoreRequest;
use App\Jobs\ImportContactsJob;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactImportController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactImportStoreRequest $request)
    {
        $path = $request->file('import')->store('contact-imports');

        ImportContactsJob::dispatch($request->user(), $path);

        return response()->noContent();
    }
}
