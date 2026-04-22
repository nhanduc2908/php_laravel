<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class LanguageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('App\Services\LanguageService', function ($app) {
            return new \App\Services\LanguageService();
        });
        
        $this->app->singleton('App\Wrappers\LanguageWrapper', function ($app) {
            return new \App\Wrappers\LanguageWrapper();
        });
    }

    public function boot(): void
    {
        $supportedLocales = ['en', 'vi', 'ja'];
        
        // Set locale from session or header
        $locale = session('locale', request()->header('Accept-Language', 'en'));
        
        if (in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
        } else {
            App::setLocale('en');
        }
        
        // Load custom language files
        $this->loadTranslationsFrom(resource_path('lang'), 'security');
        
        // Register translation commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\Language\SyncTranslations::class,
                \App\Console\Commands\Language\CreateTranslation::class,
            ]);
        }
        
        // Publish language files
        $this->publishes([
            __DIR__ . '/../../lang' => resource_path('lang/vendor/security'),
        ], 'security-lang');
    }
}