<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8|max:255',
            'name' => 'required|max:255',
            'avatar' => 'required|mimes:jpeg,jpg,png|max:2048',
            'language' => 'in:'.config('auth.user.languages')
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
            'email.unique' => 'validations.email.unique',
            'password.required' => 'validations.password.required',
            'password.confirmed' => 'validations.password_confirmation.confirmed',
            'password.min' => 'validations.password.min',
            'password.max' => 'validations.password.max',
            'name.required' => 'validations.name.required',
            'name.string' => 'validations.name.string',
            'name.max' => 'validations.name.max',
            'avatar.required' => 'validations.avatar.required',
            'avatar.mimes' => 'validations.avatar.image',
            'avatar.max' => 'validations.avatar.max_file_upload',
            'language.in' => 'validations.language.invalid_value'
        ];
    }
}
