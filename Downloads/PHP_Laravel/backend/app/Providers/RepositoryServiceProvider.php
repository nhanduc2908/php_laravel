<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repositories interfaces to implementations
        $this->app->bind(
            'App\Repositories\Contracts\UserRepositoryInterface',
            'App\Repositories\UserRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Contracts\ServerRepositoryInterface',
            'App\Repositories\ServerRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Contracts\CriteriaRepositoryInterface',
            'App\Repositories\CriteriaRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Contracts\AssessmentRepositoryInterface',
            'App\Repositories\AssessmentRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Contracts\FileRepositoryInterface',
            'App\Repositories\AssessmentFileRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Contracts\AlertRepositoryInterface',
            'App\Repositories\AlertRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Contracts\ReportRepositoryInterface',
            'App\Repositories\ReportRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Contracts\VulnerabilityRepositoryInterface',
            'App\Repositories\VulnerabilityRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Contracts\AuditRepositoryInterface',
            'App\Repositories\AuditRepository'
        );
    }

    public function boot(): void
    {
        //
    }
}