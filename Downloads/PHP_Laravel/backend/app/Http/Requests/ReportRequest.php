<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|in:full,summary,compliance,vulnerability',
            'format' => 'required|in:pdf,excel,csv',
            'server_id' => 'required_if:type,full,summary|exists:servers,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'include_charts' => 'nullable|boolean',
            'include_details' => 'nullable|boolean'
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Report type is required',
            'format.required' => 'Report format is required',
            'server_id.required_if' => 'Please select a server for this report type',
            'date_to.after_or_equal' => 'End date must be after or equal to start date'
        ];
    }
}