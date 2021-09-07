<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ServiceAccountStoreRequest;
use App\Http\Requests\Api\ServiceAccountUpdateRequest;
use App\Http\Resources\Api\ServiceAccountCollection;
use App\Http\Resources\Api\ServiceAccountResource;
use App\Models\ServiceAccount;
use Illuminate\Http\Request;

class ServiceAccountController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ServiceAccount::class, 'service_account');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\Api\ServiceAccountCollection
     */
    public function index(Request $request)
    {
        $serviceAccounts = $request->user()
            ->service_accounts()->paginate();

        return new ServiceAccountCollection($serviceAccounts);
    }

    /**
     * @param \App\Http\Requests\Api\ServiceAccountStoreRequest $request
     * @return \App\Http\Resources\Api\ServiceAccountResource
     */
    public function store(ServiceAccountStoreRequest $request)
    {
        $serviceAccount = $request->user()
            ->service_accounts()->create($request->validated());

        return new ServiceAccountResource($serviceAccount);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ServiceAccount $serviceAccount
     * @return \App\Http\Resources\Api\ServiceAccountResource
     */
    public function show(Request $request, ServiceAccount $serviceAccount)
    {
        return new ServiceAccountResource($serviceAccount);
    }

    /**
     * @param \App\Http\Requests\Api\ServiceAccountUpdateRequest $request
     * @param \App\Models\ServiceAccount $serviceAccount
     * @return \App\Http\Resources\Api\ServiceAccountResource
     */
    public function update(ServiceAccountUpdateRequest $request, ServiceAccount $serviceAccount)
    {
        $serviceAccount->update($request->validated());

        return new ServiceAccountResource($serviceAccount);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ServiceAccount $serviceAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ServiceAccount $serviceAccount)
    {
        $serviceAccount->delete();

        return response()->noContent();
    }
}
