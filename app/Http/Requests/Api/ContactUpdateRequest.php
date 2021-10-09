<?php

namespace App\Http\Requests\Api;

use App\Rules\PhoneNumbersInJsonAreE164;
use Illuminate\Foundation\Http\FormRequest;

class ContactUpdateRequest extends FormRequest
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
            'first_name' => ['string', 'max:50'],
            'last_name' => ['string', 'max:50'],
            'company' => ['string', 'max:75'],
            'phone_numbers' => ['json', new PhoneNumbersInJsonAreE164],
        ];
    }
}
