<?php

namespace App\Wrappers;

class ValidationWrapper
{
    protected $errors = [];

    public function validate($data, $rules)
    {
        $this->errors = [];
        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            foreach ($rulesArray as $rule) {
                $this->applyRule($field, $data[$field] ?? null, $rule);
            }
        }
        return empty($this->errors);
    }

    protected function applyRule($field, $value, $rule)
    {
        if ($rule === 'required' && empty($value)) {
            $this->errors[$field][] = "The {$field} field is required.";
        }
        if (strpos($rule, 'min:') === 0 && strlen($value) < substr($rule, 4)) {
            $this->errors[$field][] = "The {$field} must be at least " . substr($rule, 4) . " characters.";
        }
        // Add more rules as needed
    }

    public function errors() { return $this->errors; }
}