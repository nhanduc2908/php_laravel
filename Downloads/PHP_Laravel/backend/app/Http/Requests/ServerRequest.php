<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255|ip',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string',
            'ssh_key' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,active,inactive',
            'os_type' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Server name is required',
            'host.required' => 'Server host/IP is required',
            'host.ip' => 'Please enter a valid IP address',
            'port.required' => 'Port number is required',
            'port.integer' => 'Port must be a number',
            'port.min' => 'Port must be between 1 and 65535',
            'port.max' => 'Port must be between 1 and 65535',
            'username.required' => 'SSH username is required'
        ];
    }
}