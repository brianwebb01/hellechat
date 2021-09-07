<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['string', 'max:50', 'required_without:company'],
            'last_name' => ['string', 'max:50', 'required_without:company'],
            'company' => ['string', 'max:75', 'required_without:first_name,last_name'],
            'phone_numbers' => ['required', 'json'],
        ];
    }
}
