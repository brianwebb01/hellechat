<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends ContactUpdateRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules['first_name'][] = 'required_without:company';
        $rules['company'][] = 'required_without:first_name';
        $rules['phone_numbers'][] = 'required';

        return $rules;
    }
}
