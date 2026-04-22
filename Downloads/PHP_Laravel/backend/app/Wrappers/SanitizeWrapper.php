<?php

namespace App\Wrappers;

class SanitizeWrapper
{
    public function sanitize($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public function email($email)
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    public function url($url)
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }
}