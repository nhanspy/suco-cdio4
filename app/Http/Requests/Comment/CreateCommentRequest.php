<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
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
            'translation_id' => 'required|numeric|min:1',
            'content' => 'required|min:1'
        ];
    }

    public function messages()
    {
        return [
            'translation_id.required' => 'validations.translation_id.required',
            'translation_id.numeric' => 'validations.translation_id.numeric',
            'translation_id.min' => 'validations.translation_id.min',
            'content.required' => 'validations.content.required',
            'content.min' => 'validations.content.min',
        ];
    }
}
