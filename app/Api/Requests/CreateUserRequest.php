<?php

namespace App\Api\Requests;

use App\Api\Models\UserModel;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'email' => 'required|email|unique:'. UserModel::class .',email',
            'password' => [
                'required',
                'string',
                'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{10,}$/'
            ],
            'name' => 'required|string',
            'surname' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'Password must be 10 characters long and contain at least 1 uppercase character, 1 lowercase character and 1 number'
        ];
    }
}
