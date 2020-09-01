<?php

namespace App\Http\Requests\Push;

use Illuminate\Foundation\Http\FormRequest;

class CreatePushRequest extends FormRequest
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
            'device_type' => 'required|string|in:ios,android',
            'channel_id' => 'required|string|min:1|max:255'
        ];
    }

    public function messages()
    {
        return [
            'device_type.required' => 'validations.device_type.required',
            'device_type.string' => 'validations.device_type.string',
            'device_type.in' => 'validations.device_type.in|ios,android',
            'channel_id.required' => 'validations.channel_id.required',
            'channel_id.string' => 'validations.channel_id.string',
            'channel_id.min' => 'validations.channel_id.min',
            'channel_id.max' => 'validations.channel_id.max'
        ];
    }
}
