<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\IngestProviderMessageJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MessageIngestionController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $token)
    {
        $validToken = PersonalAccessToken::findToken($token);

        if(\is_null($validToken)){
            throw new AccessDeniedHttpException('Invalid access token', null, 403);
        }

        IngestProviderMessageJob::dispatch();

        return response('OK', 200);
    }
}
