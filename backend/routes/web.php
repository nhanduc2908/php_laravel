<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\ServerController;
use App\Http\Controllers\Web\CriteriaController;
use App\Http\Controllers\Web\AssessmentController;
use App\Http\Controllers\Web\AssessmentFileController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\SettingController;
use App\Http\Controllers\Web\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== GUEST ROUTES ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ==================== AUTHENTICATED ROUTES ====================
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/2fa', [AuthController::class, 'showTwoFactorForm'])->name('2fa.form');
    Route::post('/2fa', [AuthController::class, 'verifyTwoFactor'])->name('2fa.verify');
    Route::get('/2fa/recovery', [AuthController::class, 'showRecoveryForm'])->name('2fa.recovery');
    
    // Users
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assign-role');
    
    // Roles
    Route::resource('roles', RoleController::class);
    Route::get('/roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
    Route::post('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');
    
    // Servers
    Route::resource('servers', ServerController::class);
    Route::post('/servers/{server}/test', [ServerController::class, 'testConnection'])->name('servers.test');
    Route::post('/servers/{server}/scan', [ServerController::class, 'scan'])->name('servers.scan');
    
    // Criteria
    Route::resource('criteria', CriteriaController::class);
    Route::post('/criteria/import', [CriteriaController::class, 'import'])->name('criteria.import');
    Route::get('/criteria/export', [CriteriaController::class, 'export'])->name('criteria.export');
    
    // Assessments
    Route::resource('assessments', AssessmentController::class);
    Route::get('/assessments/{assessment}/export', [AssessmentController::class, 'export'])->name('assessments.export');
    
    // Assessment Files
    Route::resource('files', AssessmentFileController::class);
    Route::post('/files/{file}/share', [AssessmentFileController::class, 'share'])->name('files.share');
    Route::get('/files/{file}/versions', [AssessmentFileController::class, 'versions'])->name('files.versions');
    Route::post('/files/{file}/restore/{version}', [AssessmentFileController::class, 'restoreVersion'])->name('files.restore');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::post('/reports/generate', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/2fa', [ProfileController::class, 'toggleTwoFactor'])->name('profile.2fa');
    
    // Settings (Admin only)
    Route::middleware('can:manage_settings')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
    
    // Audit (Admin/Auditor only)
    Route::middleware('can:view_audit')->group(function () {
        Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
        Route::get('/audit/export', [AuditController::class, 'export'])->name('audit.export');
    });
    
    // Language Switcher
    Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
});