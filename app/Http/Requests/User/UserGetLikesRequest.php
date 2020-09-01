<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserGetLikesRequest extends FormRequest
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
            'perPage' => 'integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'perPage.integer' => 'validations.perPage.required_number_type',
            'perPage.min' => 'validations.perPage.min_1'
        ];
    }
}
