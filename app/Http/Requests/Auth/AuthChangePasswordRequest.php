<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthChangePasswordRequest extends FormRequest
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
            'password' => 'required|min:8|max:255',
            'new_password' => 'required|confirmed|min:8|max:255'
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
            'password.min' => 'validations.password.min',
            'password.max' => 'validations.password.max',
            'new_password.required' => 'validations.new_password.required',
            'new_password.confirmed' => 'validations.new_password_confirmation.confirmed',
            'new_password.min' => 'validations.new_password.min',
            'new_password.max' => 'validations.new_password.max'
        ];
    }
}
