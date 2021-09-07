<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceAccountUpdateRequest extends FormRequest
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
            'name' => ['string', 'max:50'],
            'provider' => [
                'string',
                'max:15',
                Rule::in(['twilio', 'telnyx'])
            ],
            'api_key' => ['string'],
            'api_secret' => [
                'required_if:provider,twilio',
                'string'
            ],
        ];
    }
}
