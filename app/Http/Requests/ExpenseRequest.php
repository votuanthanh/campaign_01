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
                    'event_id' => 'required|exists:events,id',
                    'goal_id' => 'required|exists:goals,id',
                    'user_id' => 'required|in:' . auth()->user()->id,
                    'cost' => 'required|numeric',
                    'time' => 'date_format:"Y-m-d"',
                    'reason' => 'nullable',
                    'type' => 'required|boolean',
                    'quantity' => 'numeric',
                    'name' => 'string',
                    'quality' => 'string',
                ];
            case 'POST':
                return [
                    'type.0.*.event_id' => 'exists:events,id',
                    'type.0.*.goal_id' => 'exists:goals,id',
                    'type.0.*.user_id' => 'in:' . auth()->user()->id,
                    'type.0.*.cost' => 'numeric',
                    'type.0.*.time' => 'date_format:"Y-m-d"',
                    'type.0.*.reason' => 'nullable',
                    'type.0.*.type' => 'boolean',
                    'type.1.*.expense.event_id' => 'exists:events,id',
                    'type.1.*.expense.goal_id' => 'exists:goals,id',
                    'type.1.*.expense.cost' => 'numeric',
                    'type.1.*.expense.time' => 'date_format:"Y-m-d"',
                    'type.1.*.expense.reason' => 'string|nullable',
                    'type.1.*.expense.type' => 'boolean',
                    'type.1.*.quantity' => 'numeric',
                    'type.1.*.name' => 'string',
                    'type.1.*.quality' => 'string',
                ];
        }
    }
}
