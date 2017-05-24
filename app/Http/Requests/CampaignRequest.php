<?php

namespace App\Http\Requests;

class CampaignRequest extends AbstractRequest
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
        $rule = [];
        
        switch ($this->method()) {
            case 'PUT': case 'PATH':
                $rule = [
                    'title' => 'required|string|max:255',
                    'description' => 'required|string',
                    'hashtag' => 'required|string|max:255|unique:campaigns,hashtag,' . $this->campaign,
                    'longitude' => 'nullable|numeric|min:-180|max:180',
                    'latitude' => 'nullable|numeric|min:-90|max:90',
                    'tags' => 'nullable|array',
                    'tags.*.name' => 'nullable|string|max:255',
                    'settings' => 'nullable|array',
                    'settings.*.key' => 'nullable|numeric',
                    'settings.*.value' => 'nullable|string|max:255',
                    'media' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:1000',
                ];
                break;
            default:
                $rule = [
                    'title' => 'required|string|max:255',
                    'description' => 'required|string',
                    'hashtag' => 'required|string|max:255|unique:campaigns,hashtag',
                    'longitude' => 'nullable|numeric|min:-180|max:180',
                    'latitude' => 'nullable|numeric|min:-90|max:90',
                    'tags' => 'nullable|array',
                    'tags.*.name' => 'nullable|string|max:255',
                    'settings' => 'nullable|array',
                    'settings.*.key' => 'nullable|numeric',
                    'settings.*.value' => 'nullable|string|max:255',
                    'media' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:1000',
                ];
                break;
        }

        return $rule;
    }
}
