<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'suite' => 'nullable|string|in:unit,feature,integration,all',
            'environment' => 'nullable|string|in:local,staging,production',
            'coverage' => 'nullable|boolean',
            'parallel' => 'nullable|boolean',
            'filter' => 'nullable|string',
            'group' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'suite.in' => 'Test suite must be unit, feature, integration, or all',
            'environment.in' => 'Environment must be local, staging, or production'
        ];
    }
}