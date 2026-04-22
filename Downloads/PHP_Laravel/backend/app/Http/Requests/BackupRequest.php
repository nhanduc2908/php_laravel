<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BackupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|in:database,files,both',
            'database' => 'nullable|string',
            'tables' => 'nullable|array',
            'include_uploads' => 'nullable|boolean',
            'compression' => 'nullable|in:none,gzip,zip',
            'encryption' => 'nullable|boolean'
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Backup type is required',
            'type.in' => 'Backup type must be database, files, or both'
        ];
    }
}