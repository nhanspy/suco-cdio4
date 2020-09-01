<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class ListCommentRequest extends FormRequest
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
            'perPage' => 'numeric|min:1|max:50',
            'page' => 'numeric|min:1'
        ];
    }

    public function messages()
    {
        return [
            'perPage.numeric' => 'validations.per_page.numeric',
            'perPage.min' => 'validations.per_page.min',
            'perPage.max' => 'validations.per_page.max',
            'page.numeric' => 'validations.page.numeric',
            'page.min' => 'validations.page.min'
        ];
    }
}
