<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;

class ProjectCreateRequest extends FormRequest
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
            'name' => 'required|string|min:1|unique:projects',
            'description' => 'required|string|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'validations.name.required',
            'name.min' => 'validations.name.min',
            'name.unique' => 'validations.name.unique',
            'description.required' => 'validations.description.min',
            'description.min' => 'validations.description.min',
        ];
    }
}
