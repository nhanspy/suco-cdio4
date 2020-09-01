<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateProfileRequest extends FormRequest
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
            'name' => 'string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'validations.email.email',
            'email.max' => 'validations.email.max',
            'name.string' => 'validations.name.string',
            'name.max' => 'validations.name.max'
        ];
    }
}
