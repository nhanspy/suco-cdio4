<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthResetPasswordRequest extends FormRequest
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
            'password' => 'required|confirmed|min:8|max:255',
            'token' => 'required',
            'email' => 'required|email|max:255'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'password.required' => 'validations.password.required',
            'password.confirmed' => 'validations.password_confirmation.confirmed',
            'password.min' => 'validations.password.min',
            'password.max' => 'validations.password.min',
            'token.required' => 'validations.token.required',
            'email.required' => 'validations.email.required',
            'email.email' => 'validations.email.email',
            'email.max' => 'validations.email.max'
        ];
    }
}
