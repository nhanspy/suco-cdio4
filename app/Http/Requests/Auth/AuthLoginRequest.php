<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
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
            'email' => 'required|email|max:255',
            'password' => 'required|max:255'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'validations.email.required',
            'email.email' => 'validations.email.email',
            'email.max' => 'validations.email.max',
            'password.required' => 'validations.password.required',
            'password.max' => 'validations.password.min'
        ];
    }
}
