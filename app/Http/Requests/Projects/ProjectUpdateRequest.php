<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
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
            'name' => 'string|min:1',
            'description' => 'string|min:1'
        ];
    }

    public function messages()
    {
        return [
            'name.min' => 'validations.name.min',
            'name.string' => 'validations.name.string',
            'description.min' => 'validations.description.min',
            'description.string' => 'validations.description.string',
        ];
    }
}
