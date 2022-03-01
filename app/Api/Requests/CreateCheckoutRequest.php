<?php

namespace App\Api\Requests;

use App\Api\Models\CheckoutModel;
use Illuminate\Foundation\Http\FormRequest;

class CreateCheckoutRequest extends FormRequest
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
            'amount' => 'required|regex:/^\d*(\.\d{2})?$/|numeric|gt:0',
            'reference' => 'required|string|min:8|max:255|unique:'. CheckoutModel::class .',reference',
        ];
    }

    public function messages()
    {
        return [
            'amount.regex' => 'Incorrect format, must be currency with 2 decimal places'
        ];
    }
}
