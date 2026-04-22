<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\LanguageService;

class LanguageTest extends TestCase
{
    protected $language;

    protected function setUp(): void
    {
        parent::setUp();
        $this->language = new LanguageService();
    }

    public function test_set_locale()
    {
        $result = $this->language->setLocale('vi');
        
        $this->assertTrue($result);
        $this->assertEquals('vi', $this->language->getLocale());
    }

    public function test_invalid_locale_returns_false()
    {
        $result = $this->language->setLocale('invalid');
        
        $this->assertFalse($result);
    }

    public function test_translate_returns_translated_string()
    {
        $this->language->setLocale('en');
        
        $translation = $this->language->translate('messages.welcome');
        
        $this->assertIsString($translation);
    }

    public function test_get_supported_locales()
    {
        $locales = $this->language->getSupportedLocales();
        
        $this->assertIsArray($locales);
        $this->assertContains('en', $locales);
        $this->assertContains('vi', $locales);
        $this->assertContains('ja', $locales);
    }

    public function test_get_language_name()
    {
        $this->assertEquals('English', $this->language->getLanguageName('en'));
        $this->assertEquals('Tiếng Việt', $this->language->getLanguageName('vi'));
        $this->assertEquals('日本語', $this->language->getLanguageName('ja'));
    }
}