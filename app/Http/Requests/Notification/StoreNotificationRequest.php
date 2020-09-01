<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'projects' => 'array|nullable'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'validations.title.required',
            'title.string' => 'validations.title.string',
            'title.max' => 'validations.title.max',
            'content.required' => 'validations.content.required',
            'content.string' => 'validations.content.string',
            'project_ids' => 'validations.project_ids.array'
        ];
    }
}
