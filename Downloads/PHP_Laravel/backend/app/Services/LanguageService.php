<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class LanguageService
{
    protected $supportedLocales = ['en', 'vi', 'ja'];
    protected $currentLocale;
    
    public function __construct()
    {
        $this->currentLocale = App::getLocale();
    }
    
    public function setLocale($locale)
    {
        if (in_array($locale, $this->supportedLocales)) {
            $this->currentLocale = $locale;
            App::setLocale($locale);
            session(['locale' => $locale]);
            return true;
        }
        return false;
    }
    
    public function getLocale()
    {
        return $this->currentLocale;
    }
    
    public function getSupportedLocales()
    {
        return $this->supportedLocales;
    }
    
    public function translate($key, $params = [], $locale = null)
    {
        $locale = $locale ?? $this->currentLocale;
        $translation = __($key, $params, $locale);
        
        if ($translation === $key) {
            // Fallback to English
            $translation = __($key, $params, 'en');
        }
        
        return $translation;
    }
    
    public function getAllTranslations($group = null)
    {
        $cacheKey = "translations.{$this->currentLocale}";
        
        return Cache::remember($cacheKey, 3600, function () {
            $translations = [];
            $langPath = resource_path("lang/{$this->currentLocale}");
            
            if (File::isDirectory($langPath)) {
                foreach (File::files($langPath) as $file) {
                    $key = pathinfo($file, PATHINFO_FILENAME);
                    $translations[$key] = include $file;
                }
            }
            
            return $translations;
        });
    }
    
    public function getLanguageName($locale)
    {
        $names = [
            'en' => 'English',
            'vi' => 'Tiếng Việt',
            'ja' => '日本語'
        ];
        
        return $names[$locale] ?? $locale;
    }
    
    public function getLanguageFlag($locale)
    {
        $flags = [
            'en' => '🇺🇸',
            'vi' => '🇻🇳',
            'ja' => '🇯🇵'
        ];
        
        return $flags[$locale] ?? '🏳️';
    }
}