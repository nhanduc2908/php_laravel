<?php

namespace App\Repositories;

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class LanguageRepository extends BaseRepository
{
    public function model()
    {
        return Translation::class;
    }

    public function getTranslations($locale, $group = null)
    {
        $cacheKey = "translations.{$locale}";
        
        if ($group) {
            $cacheKey .= ".{$group}";
        }
        
        return Cache::remember($cacheKey, 3600, function () use ($locale, $group) {
            $query = $this->model->where('locale', $locale);
            
            if ($group) {
                $query->where('group', $group);
            }
            
            return $query->pluck('value', 'key')->toArray();
        });
    }

    public function saveTranslation($locale, $key, $value, $group = null)
    {
        Cache::forget("translations.{$locale}");
        
        return $this->model->updateOrCreate(
            ['locale' => $locale, 'key' => $key, 'group' => $group],
            ['value' => $value]
        );
    }

    public function importFromFile($locale, $file)
    {
        $translations = include $file;
        $saved = 0;
        
        foreach ($translations as $key => $value) {
            $this->saveTranslation($locale, $key, $value);
            $saved++;
        }
        
        return $saved;
    }

    public function exportToFile($locale, $group = null)
    {
        $translations = $this->getTranslations($locale, $group);
        
        $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
        
        $filename = $group ? "{$group}.php" : "all.php";
        $path = storage_path("app/exports/lang_{$locale}_{$filename}");
        
        File::put($path, $content);
        
        return $path;
    }

    public function getMissingTranslations($locale, $baseLocale = 'en')
    {
        $baseTranslations = $this->getTranslations($baseLocale);
        $targetTranslations = $this->getTranslations($locale);
        
        $missing = [];
        
        foreach ($baseTranslations as $key => $value) {
            if (!isset($targetTranslations[$key])) {
                $missing[$key] = $value;
            }
        }
        
        return $missing;
    }

    public function getLanguageStats()
    {
        $locales = ['en', 'vi', 'ja'];
        $stats = [];
        
        foreach ($locales as $locale) {
            $count = $this->model->where('locale', $locale)->count();
            $stats[$locale] = $count;
        }
        
        return $stats;
    }
}