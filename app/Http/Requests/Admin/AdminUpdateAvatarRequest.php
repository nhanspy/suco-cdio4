<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateAvatarRequest extends FormRequest
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
            'avatar' => 'required|mimes:jpeg,jpg,png|max:2048'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'avatar.required' => 'validations.avatar.required',
            'avatar.mimes' => 'validations.avatar.image',
            'avatar.max' => 'validations.avatar.max_file_upload'
        ];
    }
}
