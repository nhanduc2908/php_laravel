<?php

namespace App\Jobs;

use App\Models\Translation;
use App\Services\ApiCallService;
use Illuminate\Support\Facades\Log;

class TranslateJob extends BaseJob
{
    protected $locale;
    protected $key;
    protected $text;

    public function __construct($locale, $key, $text)
    {
        $this->locale = $locale;
        $this->key = $key;
        $this->text = $text;
    }

    public function handle(ApiCallService $api)
    {
        // Use external translation API (e.g., Google Translate)
        $translated = $api->makeRequest('POST', 'https://translation.googleapis.com/language/translate/v2', [
            'q' => $this->text,
            'target' => $this->locale,
            'source' => 'en',
            'format' => 'text'
        ]);
        
        if ($translated && isset($translated['data']['translations'][0]['translatedText'])) {
            Translation::updateOrCreate(
                ['locale' => $this->locale, 'key' => $this->key],
                ['value' => $translated['data']['translations'][0]['translatedText']]
            );
            
            Log::info('Translation completed', [
                'locale' => $this->locale,
                'key' => $this->key
            ]);
        } else {
            Log::warning('Translation failed', [
                'locale' => $this->locale,
                'key' => $this->key
            ]);
        }
    }
}