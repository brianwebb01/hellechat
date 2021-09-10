<?php

namespace App\Http\Middleware\Services\Twilio;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Twilio\Security\RequestValidator as TwilioRequestValidator;

class RequestValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (App::environment('testing')) {
            return $next($request);
        }

        $requestValidator = new TwilioRequestValidator(config('services.twilio.auth_token'));

        $requestData = $request->toArray();

        // Switch to the body content if this is a JSON request.
        if (array_key_exists('bodySHA256', $requestData)) {
            $requestData = $request->getContent();
        }

        $isValid = $requestValidator->validate(
            $request->header('X-Twilio-Signature'),
            $request->fullUrl(),
            $requestData
        );

        if ($isValid) {
            return $next($request);
        } else {
            return new Response('You are not Twilio :(', 403);
        }
    }
}
