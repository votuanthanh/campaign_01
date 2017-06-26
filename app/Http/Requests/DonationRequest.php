<?php

namespace App\Http\Requests;

class DonationRequest extends AbstractRequest
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
                    'goal_id' => 'required|exists:goals,id',
                    'value' => 'required|numeric',
                ];
            default:
                return [
                    'goal_id' => 'required|exists:goals,id',
                    'event_id' => 'required|exists:events,id',
                    'value' => 'required|numeric',
                ];
        }
    }
}
