<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserStoreRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param \App\Http\Requests\Api\UserStoreRequest $request
     * @return \App\Http\Resources\Api\UserResource
     */
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }
}
