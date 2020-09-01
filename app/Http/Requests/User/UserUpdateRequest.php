<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'email' => 'email|max:255',
            'name' => 'max:255',
            'language' => 'max:255|in:'.config('auth.user.languages'),
            'position' => 'max:255'
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'validations.email.email',
            'email.max' => 'validations.email.max',
            'name.max' => 'validations.name.max',
            'language.max' => 'validations.language.max',
            'language.in' => 'validations.language.invalid_value',
            'position' => 'validations.position.max'
        ];
    }
}
