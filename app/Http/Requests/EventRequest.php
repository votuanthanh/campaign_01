<?php

namespace App\Http\Requests;

class EventRequest extends AbstractRequest
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
            case 'PATCH':
            case 'PUT':
                return [
                    'title' => 'required|string',
                    'description' => 'required|string',
                    'files.*' => 'string',
                    'longitude' => 'numeric|min:-180|max:180',
                    'latitude' => 'numeric|min:-90|max:90',
                ];
            default:
                return [
                    'title' => 'required|string',
                    'description' => 'required|string',
                    'longitude' => 'nullable|numeric|min:-180|max:180',
                    'latitude' => 'nullable|numeric|min:-90|max:90',
                    'settings' => 'required',
                ];
        }
    }
}
