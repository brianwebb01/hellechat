<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class NumberStoreRequest extends NumberUpdateRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules['service_account_id'][] = 'required';
        $rules['phone_number'][] = 'required';
        $rules['friendly_label'][] = 'required';

        return $rules;
    }
}
