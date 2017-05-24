<?php

namespace App\Http\Requests;

class CreateCampaignRequest extends AbstractRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'hashtag' => 'required|string|max:255|unique:campaigns,hashtag',
            'longitude' => 'nullable|numeric|min:-180|max:180',
            'latitude' => 'nullable|numeric|min:-90|max:90',
            'tagName' => 'nullable|array',
            'settings' => 'nullable|array',
            'media' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:1000',
        ];
    }
}
