<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateProfileRequest extends FormRequest
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
            'name' => 'max:255',
            'email' => 'email|max:255|unique:users',
            'language' => 'max:255',
            'position' => 'max:255'
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'validations.email.email',
            'email.max' => 'validations.email.max',
            'email.unique' => 'validations.email.unique',
            'name.max' => 'validations.name.max',
            'language.max' => 'validations.language.max',
            'position' => 'validations.position.max'
        ];
    }
}
