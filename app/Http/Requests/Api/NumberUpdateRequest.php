<?php

namespace App\Http\Requests\Api;

use App\Rules\ServiceAccountOwnershipRequired;
use Illuminate\Foundation\Http\FormRequest;

class NumberUpdateRequest extends FormRequest
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
            'service_account_id' => [
                'integer',
                new ServiceAccountOwnershipRequired
            ],
            'phone_number' => ['string', 'max:15'],
            'friendly_label' => ['string', 'max:50'],
            'external_identity' => ['nullable'],
            'sip_registration_url' => ['url']
        ];
    }
}
