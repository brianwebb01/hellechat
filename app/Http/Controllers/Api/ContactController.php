<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactStoreRequest;
use App\Http\Requests\Api\ContactUpdateRequest;
use App\Http\Resources\Api\ContactCollection;
use App\Http\Resources\Api\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Contact::class, 'contact');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\Api\ContactCollection
     */
    public function index(Request $request)
    {
        $contacts = $request->user()->contacts()
            ->orderBy(DB::raw('COALESCE(last_name, company, first_name)'), 'asc')
            ->paginate();

        return new ContactCollection($contacts);
    }

    /**
     * @param \App\Http\Requests\Api\ContactStoreRequest $request
     * @return \App\Http\Resources\Api\ContactResource
     */
    public function store(ContactStoreRequest $request)
    {
        $contact = $request->user()->contacts()->create($request->validated());

        return new ContactResource($contact);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contact $contact
     * @return \App\Http\Resources\Api\ContactResource
     */
    public function show(Request $request, Contact $contact)
    {
        return new ContactResource($contact);
    }

    /**
     * @param \App\Http\Requests\Api\ContactUpdateRequest $request
     * @param \App\Models\Contact $contact
     * @return \App\Http\Resources\Api\ContactResource
     */
    public function update(ContactUpdateRequest $request, Contact $contact)
    {
        $contact->update($request->validated());

        return new ContactResource($contact);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Contact $contact)
    {
        $contact->delete();

        return response()->noContent();
    }
}
