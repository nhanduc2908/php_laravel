<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'server_id' => 'required|exists:servers,id',
            'answers' => 'required|array',
            'answers.*.criteria_id' => 'required|exists:criteria,id',
            'answers.*.value' => 'required|boolean|numeric|string',
            'answers.*.evidence' => 'nullable|string',
            'answers.*.note' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'server_id.required' => 'Please select a server',
            'server_id.exists' => 'Selected server is invalid',
            'answers.required' => 'Assessment answers are required',
            'answers.*.criteria_id.required' => 'Criteria ID is required',
            'answers.*.value.required' => 'Answer value is required'
        ];
    }
}