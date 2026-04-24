<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = auth()->id();
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'avatar' => 'nullable|image|max:2048',
            'bio' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Full name is required',
            'email.required' => 'Email address is required',
            'email.unique' => 'This email is already taken',
            'avatar.image' => 'Avatar must be an image file',
            'avatar.max' => 'Avatar size cannot exceed 2MB'
        ];
    }
}