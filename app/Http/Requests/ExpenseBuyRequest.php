<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseBuyRequest extends FormRequest
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
                    'expense.goal_id' => 'required|exists:goals,id',
                    'expense.cost' => 'required|numeric',
                    'expense.time' => 'date_format:"Y-m-d"',
                    'expense.reason' => 'required',
                    'expense.type' => 'required|numeric',
                    'name' => 'required|string',
                    'quantity' => 'required|numeric',
                    'quality' => 'required|string',
                ];
            case 'POST':
                return [
                    'expense.event_id' => 'required|exists:events,id',
                    'expense.goal_id' => 'required|exists:goals,id',
                    'expense.cost' => 'required|numeric',
                    'expense.time' => 'date_format:"Y-m-d"',
                    'expense.reason' => 'required',
                    'expense.type' => 'required|numeric',
                    'name' => 'required|string',
                    'quantity' => 'required|numeric',
                    'quality' => 'required|string',
                ];
        }
    }
}
