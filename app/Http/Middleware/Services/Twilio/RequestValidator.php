<?php

namespace App\Http\Middleware\Services\Twilio;

use App\Models\ServiceAccount;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        if (App::environment(['testing', 'local'])) {
            return $next($request);
        }

        $userHashId = $request->route('userHashId');

        try {
            $user = User::findByHashId($userHashId);
        } catch (ModelNotFoundException $e) {
            return response('Invalid User', 403);
        }

        try {
            $sa = $user->service_accounts()
                ->where('provider', ServiceAccount::PROVIDER_TWILIO)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response('Invalid Service Account', 403);
        }

        $requestValidator = new TwilioRequestValidator($sa->api_secret);

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
            return response('You are not Twilio :(', 403);
        }
    }
}
