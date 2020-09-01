<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
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
            'password' => 'required|confirmed|min:8|max:255',
            'name' => 'required|string|max:255',
            'avatar' => 'required|mimes:jpeg,jpg,png|max:2048'
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
            'password.confirmed' => 'validations.password_confirmation.confirmed',
            'password.min' => 'validations.password.min',
            'password.max' => 'validations.password.max',
            'name.required' => 'validations.name.required',
            'name.string' => 'validations.name.string',
            'name.max' => 'validations.name.max',
            'avatar.required' => 'validations.avatar.required',
            'avatar.mimes' => 'validations.avatar.image',
            'avatar.max' => 'validations.avatar.max_file_upload'
        ];
    }
}
