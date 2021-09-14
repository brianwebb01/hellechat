<?php

namespace App\Http\Requests\Api;

use App\Models\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MessageStoreRequest extends FormRequest
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
            'contact_id' => ['integer'],
            'from' => ['required', 'string', 'max:15'],
            'to' => ['required', 'string', 'max:15'],
            'body' => ['string'],
            'error_code' => ['string', 'max:20'],
            'error_message' => ['string'],
            'direction' => [
                'required',
                'string',
                'max:15',
                Rule::in([Message::DIRECTION_IN, Message::DIRECTION_OUT])
            ],
            'status' => ['required', 'string', 'max:15'],
            'num_media' => ['integer'],
            'media' => ['string'],
            'external_identity' => [''],
            'external_date_created' => [''],
            'external_date_updated' => [''],
        ];
    }
}
