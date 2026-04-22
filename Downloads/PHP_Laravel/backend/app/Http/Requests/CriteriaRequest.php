<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CriteriaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $criteriaId = $this->route('id');
        
        return [
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|string|max:50|unique:criteria,code,' . $criteriaId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0|max:100',
            'status' => 'nullable|in:active,inactive',
            'answer_type' => 'nullable|in:yes_no,score,text,multiple_choice'
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Please select a category',
            'category_id.exists' => 'Selected category is invalid',
            'code.required' => 'Criteria code is required',
            'code.unique' => 'This criteria code already exists',
            'name.required' => 'Criteria name is required',
            'weight.required' => 'Weight is required',
            'weight.numeric' => 'Weight must be a number',
            'weight.min' => 'Weight must be at least 0',
            'weight.max' => 'Weight cannot exceed 100'
        ];
    }
}