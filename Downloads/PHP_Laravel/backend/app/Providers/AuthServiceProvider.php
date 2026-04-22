<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
        
        // Super Admin has all permissions
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super_admin')) {
                return true;
            }
        });
        
        // Define permissions gates
        Gate::define('view-users', function ($user) {
            return $user->hasPermission('view_users');
        });
        
        Gate::define('create-users', function ($user) {
            return $user->hasPermission('create_users');
        });
        
        Gate::define('edit-users', function ($user) {
            return $user->hasPermission('edit_users');
        });
        
        Gate::define('delete-users', function ($user) {
            return $user->hasPermission('delete_users');
        });
        
        Gate::define('view-servers', function ($user) {
            return $user->hasPermission('view_servers');
        });
        
        Gate::define('manage-servers', function ($user) {
            return $user->hasPermission('manage_servers');
        });
        
        Gate::define('run-assessments', function ($user) {
            return $user->hasPermission('run_assessments');
        });
        
        Gate::define('view-reports', function ($user) {
            return $user->hasPermission('view_reports');
        });
        
        Gate::define('manage-files', function ($user) {
            return $user->hasPermission('manage_files');
        });
        
        Gate::define('view-audit', function ($user) {
            return $user->hasPermission('view_audit');
        });
    }
}