<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFilterRequest extends FormRequest
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
            'perPage' => 'required|min:1',
            'state' => 'in:all,active,deleted|nullable',
            'field' => 'in:name,email|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'perPage.required' => 'validations.perPage.required',
            'perPage.min' => 'validations.perPage.min_1',
            'state.in' => 'validations.user.filter.state.in:all,active,deleted',
            'field.in' => 'validations.user.filter.field.in:name,email',
        ];
    }
}
