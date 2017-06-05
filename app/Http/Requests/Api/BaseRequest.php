<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

abstract BaseRequest extends FormRequest
{
    protected function formatErrors(Validator $validator)
    {
        return [
            'message' => [
                'status' => false,
                'code' => VALIDATOR_ERROR,
                'description' => $validator->errors()->all(),
            ]
        ];
    }
}
