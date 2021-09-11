<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RequiresUserHashId
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
        $userHashId = $request->route('userHashId');

        try {
            $user = User::findByHashId($userHashId);
        } catch (ModelNotFoundException) {
            return response('Unauthorized', 403);
        }

        return $next($request);
    }
}
