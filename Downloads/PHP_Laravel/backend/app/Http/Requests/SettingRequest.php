<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'key' => 'required|string|max:255',
            'value' => 'nullable',
            'group' => 'nullable|string|max:100',
            'type' => 'nullable|in:string,integer,boolean,json,array'
        ];
    }

    public function messages()
    {
        return [
            'key.required' => 'Setting key is required',
            'type.in' => 'Setting type must be string, integer, boolean, json, or array'
        ];
    }
}