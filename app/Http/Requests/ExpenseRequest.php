<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
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
                    'cost' => 'required|numeric',
                    'time' => 'date_format:"Y-m-d"',
                    'reason' => 'required',
                    'type' => 'required|numeric',
                ];
            case 'POST':
                return [
                    'event_id' => 'required|exists:events,id',
                    'goal_id' => 'required|exists:goals,id',
                    'cost' => 'required|numeric',
                    'time' => 'date_format:"Y-m-d"',
                    'reason' => 'required',
                    'type' => 'required|numeric',
                ];
        }
    }
}
