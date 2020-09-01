<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationRequest extends FormRequest
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
            'content' => 'string'
        ];
    }

    public function messages()
    {
        return [
            'title.string' => 'validations.title.string',
            'title.max' => 'validations.title.max',
            'content.string' => 'validations.content.string'
        ];
    }
}
