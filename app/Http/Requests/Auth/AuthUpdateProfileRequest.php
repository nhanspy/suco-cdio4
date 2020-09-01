<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthUpdateProfileRequest extends FormRequest
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
            'name' => 'string|max:255',
            'language' => 'string|max:255|in:'.config('auth.user.languages'),
            'position' => 'string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'validations.name.string',
            'name.max' => 'validations.name.max',
            'language.string' => 'validations.language.string',
            'language.max' => 'validations.language.max',
            'language.in' => 'validations.language.invalid_value',
            'position' => 'validations.position.max',
            'position.string' => 'validations.position.string'
        ];
    }
}
