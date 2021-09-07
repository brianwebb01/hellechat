<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceAccountStoreRequest extends ServiceAccountUpdateRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules['name'][] = 'required';
        $rules['provider'][] = 'required';
        $rules['api_key'][] = 'required';

        return $rules;
    }
}
