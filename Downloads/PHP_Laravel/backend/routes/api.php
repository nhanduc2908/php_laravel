<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\RoleController;
use App\Http\Controllers\API\V1\ServerController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\CriteriaController;
use App\Http\Controllers\API\V1\AssessmentController;
use App\Http\Controllers\API\V1\AssessmentFileController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\VulnerabilityController;
use App\Http\Controllers\API\V1\AlertController;
use App\Http\Controllers\API\V1\ReportController;
use App\Http\Controllers\API\V1\BackupController;
use App\Http\Controllers\API\V1\ProfileController;
use App\Http\Controllers\API\V1\AuditController;
use App\Http\Controllers\API\V1\SettingController;
use App\Http\Controllers\API\V1\MenuController;
use App\Http\Controllers\API\V1\TestingController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    
    // ==================== PUBLIC ROUTES ====================
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);
    Route::get('/health', fn() => response()->json(['status' => 'ok', 'timestamp' => now()]));
    
    // ==================== AUTHENTICATED ROUTES ====================
    Route::middleware(['auth:api'])->group(function () {
        
        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        
        // Users (Admin only)
        Route::apiResource('users', UserController::class);
        Route::post('/users/{id}/assign-role', [UserController::class, 'assignRole']);
        Route::get('/users/{id}/permissions', [UserController::class, 'permissions']);
        
        // Roles (Admin only)
        Route::apiResource('roles', RoleController::class);
        Route::post('/roles/{id}/permissions', [RoleController::class, 'assignPermission']);
        Route::delete('/roles/{id}/permissions/{permissionId}', [RoleController::class, 'revokePermission']);
        Route::get('/roles/{id}/permissions', [RoleController::class, 'permissions']);
        
        // Servers
        Route::apiResource('servers', ServerController::class);
        Route::post('/servers/{id}/test-connection', [ServerController::class, 'testConnection']);
        Route::post('/servers/{id}/scan', [ServerController::class, 'scan']);
        Route::get('/servers/{id}/metrics', [ServerController::class, 'metrics']);
        
        // Categories
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{id}', [CategoryController::class, 'show']);
        Route::get('/categories/{id}/criteria', [CategoryController::class, 'criteria']);
        Route::get('/categories/tree', [CategoryController::class, 'tree']);
        
        // Criteria
        Route::apiResource('criteria', CriteriaController::class);
        Route::post('/criteria/import', [CriteriaController::class, 'import']);
        Route::get('/criteria/export', [CriteriaController::class, 'export']);
        Route::get('/criteria/search', [CriteriaController::class, 'search']);
        
        // Assessments
        Route::post('/assessments/run', [AssessmentController::class, 'run']);
        Route::get('/assessments/{id}/result', [AssessmentController::class, 'result']);
        Route::get('/assessments/history', [AssessmentController::class, 'history']);
        Route::get('/assessments/compliance/{serverId}', [AssessmentController::class, 'compliance']);
        Route::get('/assessments/score/{serverId}', [AssessmentController::class, 'score']);
        Route::post('/assessments/compare', [AssessmentController::class, 'compare']);
        Route::get('/assessments/{id}/export', [AssessmentController::class, 'export']);
        
        // Assessment Files
        Route::apiResource('assessment-files', AssessmentFileController::class);
        Route::post('/assessment-files/{id}/share/{userId}', [AssessmentFileController::class, 'share']);
        Route::get('/assessment-files/{id}/versions', [AssessmentFileController::class, 'versions']);
        Route::post('/assessment-files/{id}/upload', [AssessmentFileController::class, 'upload']);
        Route::get('/assessment-files/{id}/download', [AssessmentFileController::class, 'download']);
        
        // Dashboard
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
        Route::get('/dashboard/charts', [DashboardController::class, 'charts']);
        Route::get('/dashboard/recent', [DashboardController::class, 'recent']);
        Route::get('/dashboard/trends', [DashboardController::class, 'trends']);
        Route::get('/dashboard/compliance', [DashboardController::class, 'compliance']);
        
        // Vulnerabilities
        Route::get('/vulnerabilities', [VulnerabilityController::class, 'index']);
        Route::get('/vulnerabilities/{id}', [VulnerabilityController::class, 'show']);
        Route::get('/vulnerabilities/cve/{cve}', [VulnerabilityController::class, 'cveLookup']);
        Route::post('/vulnerabilities/{id}/mark-fixed', [VulnerabilityController::class, 'markFixed']);
        Route::get('/vulnerabilities/severity/stats', [VulnerabilityController::class, 'bySeverity']);
        Route::get('/vulnerabilities/server/{serverId}', [VulnerabilityController::class, 'byServer']);
        Route::get('/vulnerabilities/statistics', [VulnerabilityController::class, 'statistics']);
        
        // Alerts
        Route::get('/alerts', [AlertController::class, 'index']);
        Route::get('/alerts/{id}', [AlertController::class, 'show']);
        Route::post('/alerts/{id}/read', [AlertController::class, 'markRead']);
        Route::post('/alerts/bulk-read', [AlertController::class, 'bulkRead']);
        Route::post('/alerts/{id}/resolve', [AlertController::class, 'resolve']);
        Route::post('/alerts/{id}/ignore', [AlertController::class, 'ignore']);
        Route::delete('/alerts/clean', [AlertController::class, 'cleanOld']);
        
        // Reports
        Route::post('/reports/generate', [ReportController::class, 'generate']);
        Route::get('/reports/download/{id}', [ReportController::class, 'download']);
        Route::post('/reports/schedule', [ReportController::class, 'schedule']);
        Route::get('/reports/templates', [ReportController::class, 'templates']);
        
        // Backup (Admin only)
        Route::post('/backup/create', [BackupController::class, 'create']);
        Route::post('/backup/restore', [BackupController::class, 'restore']);
        Route::get('/backup/list', [BackupController::class, 'list']);
        Route::get('/backup/download/{id}', [BackupController::class, 'download']);
        Route::delete('/backup/{id}', [BackupController::class, 'delete']);
        
        // Profile
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar']);
        Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
        Route::post('/profile/2fa/enable', [ProfileController::class, 'enable2FA']);
        Route::post('/profile/2fa/disable', [ProfileController::class, 'disable2FA']);
        Route::get('/profile/sessions', [ProfileController::class, 'sessions']);
        
        // Audit (Admin/Auditor only)
        Route::get('/audit/logs', [AuditController::class, 'index']);
        Route::get('/audit/logs/{id}', [AuditController::class, 'show']);
        Route::get('/audit/user/{userId}', [AuditController::class, 'userLogs']);
        Route::get('/audit/export', [AuditController::class, 'export']);
        
        // Settings (Admin only)
        Route::get('/settings', [SettingController::class, 'index']);
        Route::put('/settings', [SettingController::class, 'update']);
        Route::get('/settings/group/{group}', [SettingController::class, 'byGroup']);
        Route::post('/settings/reset/{group}', [SettingController::class, 'reset']);
        
        // Dynamic Menu
        Route::get('/menu', [MenuController::class, 'getMenu']);
        Route::get('/menu/sidebar', [MenuController::class, 'getSidebar']);
        Route::get('/menu/permissions', [MenuController::class, 'getPermissions']);
        
        // Testing (Super Admin only)
        Route::post('/testing/run', [TestingController::class, 'run']);
        Route::get('/testing/results', [TestingController::class, 'results']);
        Route::get('/testing/coverage', [TestingController::class, 'coverage']);
        Route::get('/testing/history', [TestingController::class, 'history']);
    });
});