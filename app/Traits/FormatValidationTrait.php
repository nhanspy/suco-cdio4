<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;

trait FormatValidationTrait
{
    public function format(Validator $validator)
    {
        $error = $validator->errors()->getMessages();
        $obj = $validator->failed();
        $result = [];

        foreach ($obj as $input => $rules) {
            $value = reset($rules); // give first element in array, return false if empty

            if (!$value) {
                $value = '';
            } else {
                $value = join(',', $value);
            }

            $result[$input] = [
                'message' => $error[$input][0],
                'value' => $value
            ];
        }

        return $result;
    }
}
