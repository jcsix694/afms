<?php

namespace App\Api\Requests;

use App\Api\Models\CheckoutModel;
use App\Api\Models\PaymentModel;
use Illuminate\Foundation\Http\FormRequest;

class CreateRefundRequest extends FormRequest
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
            'paymentId' => 'required|string',
            'amount' => 'required|regex:/^\d*(\.\d{2})?$/|numeric',
        ];
    }

    public function messages()
    {
        return [
            'amount.regex' => 'Incorrect format, must be currency with 2 decimal places'
        ];
    }
}
