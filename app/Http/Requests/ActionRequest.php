<?php

namespace App\Http\Requests;

class ActionRequest extends AbstractRequest
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
        switch ($this->method()) {
            case 'PUT':
            case 'PATCH':
                return [
                    'caption' => 'required|max:255',
                    'description' => 'max:500',
                    'upload.image.*' => 'image|max:500',
                    'upload.video.*' => 'url',
                ];
        }
    }
}
