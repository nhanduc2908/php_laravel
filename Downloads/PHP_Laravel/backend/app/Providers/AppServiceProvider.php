<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register custom services
        $this->app->singleton('App\Services\AssessmentService', function ($app) {
            return new \App\Services\AssessmentService();
        });
    }

    public function boot(): void
    {
        // Fix for MySQL < 5.7.7
        Schema::defaultStringLength(191);
        
        // Custom validation rules
        Validator::extend('cve_format', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^CVE-\d{4}-\d{4,}$/', $value);
        });
        
        Validator::extend('server_ssh', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            return !empty($data['ssh_key']) || !empty($data['password']);
        });
        
        // Share data with all views
        view()->composer('*', function ($view) {
            $view->with('appName', config('app.name'));
        });
    }
}
