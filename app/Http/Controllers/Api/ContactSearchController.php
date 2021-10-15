<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactSearchRequest;
use App\Http\Resources\Api\ContactCollection;
use Illuminate\Http\Request;

class ContactSearchController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(ContactSearchRequest $request)
    {
        $query = $request->get('query');

        $contacts = $request->user()->contacts()
            ->where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('company', 'LIKE', "%{$query}%")
            ->orWhere('phone_numbers', 'LIKE', "%{$query}%")
            ->get();

        return new ContactCollection($contacts);
    }
}
