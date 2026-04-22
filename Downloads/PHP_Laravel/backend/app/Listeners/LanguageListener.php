<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\LanguageService;

class LanguageListener extends BaseListener
{
    protected $language;

    public function __construct(LanguageService $language)
    {
        $this->language = $language;
    }

    public function handleLogin(Login $event)
    {
        $user = $event->user;
        
        // Get user's preferred language from database (if stored)
        $preferredLocale = $user->preferred_locale ?? 'en';
        
        // Set locale for the session
        $this->language->setLocale($preferredLocale);
        session(['locale' => $preferredLocale]);
    }

    public function handleLanguageChange($event)
    {
        $locale = $event->locale ?? 'en';
        $this->language->setLocale($locale);
        
        if (auth()->check()) {
            auth()->user()->update(['preferred_locale' => $locale]);
        }
    }

    public function subscribe($events)
    {
        $events->listen(Login::class, [LanguageListener::class, 'handleLogin']);
        
        // Custom event for language change
        $events->listen('language.changed', [LanguageListener::class, 'handleLanguageChange']);
    }
}