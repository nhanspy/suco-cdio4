<?php

namespace App\Http\Requests;

use App\Exceptions\ImportException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ImportTranslationRequest extends FormRequest
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
            'file' => 'required|mimes:xlsx,xls,ods,zip|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'validations.file.required',
            'file.mimes' => 'validations.file.mimes',
            'file.max' => 'validations.file.max',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new ImportException($validator);
    }
}
