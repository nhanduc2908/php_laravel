<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\LanguageService;

class LanguageMiddleware
{
    protected $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function handle($request, Closure $next)
    {
        $locale = $request->header('Accept-Language', 'en');
        
        $supportedLocales = ['en', 'vi', 'ja'];
        
        if (in_array($locale, $supportedLocales)) {
            app()->setLocale($locale);
            $this->languageService->setLocale($locale);
        } else {
            app()->setLocale('en');
        }

        $response = $next($request);
        
        $response->headers->set('Content-Language', app()->getLocale());

        return $response;
    }
}