<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $fileId = $this->route('id');
        
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'server_id' => 'required|exists:servers,id',
            'status' => 'nullable|in:draft,published,archived',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'due_date' => 'nullable|date|after:today',
            'priority' => 'nullable|in:low,medium,high,critical'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'File title is required',
            'content.required' => 'File content is required',
            'server_id.required' => 'Please select a server',
            'server_id.exists' => 'Selected server is invalid',
            'due_date.after' => 'Due date must be in the future'
        ];
    }
}