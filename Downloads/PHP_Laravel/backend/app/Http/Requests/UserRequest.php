<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('id');
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'nullable|boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Full name is required',
            'email.required' => 'Email address is required',
            'email.unique' => 'This email is already taken',
            'role_id.required' => 'Please select a role',
            'role_id.exists' => 'Selected role is invalid',
            'password.min' => 'Password must be at least 8 characters'
        ];
    }
}