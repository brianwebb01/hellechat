<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\NumberStoreRequest;
use App\Http\Requests\Api\NumberUpdateRequest;
use App\Http\Resources\Api\NumberCollection;
use App\Http\Resources\Api\NumberResource;
use App\Models\Number;
use Illuminate\Http\Request;

class NumberController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\Api\NumberCollection
     */
    public function index(Request $request)
    {
        $numbers = Number::paginate();

        return new NumberCollection($numbers);
    }

    /**
     * @param \App\Http\Requests\Api\NumberStoreRequest $request
     * @return \App\Http\Resources\Api\NumberResource
     */
    public function store(NumberStoreRequest $request)
    {
        $number = Number::create($request->validated());

        return new NumberResource($number);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Number $number
     * @return \App\Http\Resources\Api\NumberResource
     */
    public function show(Request $request, Number $number)
    {
        return new NumberResource($number);
    }

    /**
     * @param \App\Http\Requests\Api\NumberUpdateRequest $request
     * @param \App\Models\Number $number
     * @return \App\Http\Resources\Api\NumberResource
     */
    public function update(NumberUpdateRequest $request, Number $number)
    {
        $number->update($request->validated());

        return new NumberResource($number);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Number $number
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Number $number)
    {
        $number->delete();

        return response()->noContent();
    }
}
