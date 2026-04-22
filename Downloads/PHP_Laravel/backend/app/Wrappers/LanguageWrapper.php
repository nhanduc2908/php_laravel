<?php

namespace App\Wrappers;

class LanguageWrapper
{
    protected $locale = 'en';
    protected $translations = [];

    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function trans($key, $params = [])
    {
        $value = $this->translations[$key] ?? $key;
        foreach ($params as $k => $v) {
            $value = str_replace(":{$k}", $v, $value);
        }
        return $value;
    }

    public function load($path)
    {
        $file = "{$path}/{$this->locale}.php";
        if (file_exists($file)) {
            $this->translations = array_merge($this->translations, require $file);
        }
        return $this;
    }
}