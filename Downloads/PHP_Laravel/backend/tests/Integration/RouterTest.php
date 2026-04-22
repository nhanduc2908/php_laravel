<?php

namespace Tests\Integration;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class RouterTest extends TestCase
{
    public function test_api_v1_routes_are_registered()
    {
        $routes = Route::getRoutes();
        $apiRoutes = collect($routes)->filter(function ($route) {
            return strpos($route->uri(), 'api/v1') === 0;
        });
        
        $this->assertGreaterThan(0, $apiRoutes->count());
    }

    public function test_auth_routes_exist()
    {
        $this->assertNotNull(Route::getRoutes()->getByRoute('POST', 'api/v1/auth/login'));
        $this->assertNotNull(Route::getRoutes()->getByRoute('POST', 'api/v1/auth/register'));
        $this->assertNotNull(Route::getRoutes()->getByRoute('POST', 'api/v1/auth/logout'));
        $this->assertNotNull(Route::getRoutes()->getByRoute('POST', 'api/v1/auth/refresh'));
    }

    public function test_user_routes_exist()
    {
        $this->assertNotNull(Route::getRoutes()->getByRoute('GET', 'api/v1/users'));
        $this->assertNotNull(Route::getRoutes()->getByRoute('POST', 'api/v1/users'));
        $this->assertNotNull(Route::getRoutes()->getByRoute('GET', 'api/v1/users/{id}'));
        $this->assertNotNull(Route::getRoutes()->getByRoute('PUT', 'api/v1/users/{id}'));
        $this->assertNotNull(Route::getRoutes()->getByRoute('DELETE', 'api/v1/users/{id}'));
    }

    public function test_server_routes_exist()
    {
        $this->assertNotNull(Route::getRoutes()->getByRoute('GET', 'api/v1/servers'));
        $this->assertNotNull(Route::getRoutes()->getByRoute('POST', 'api/v1/servers'));
        $this->assertNotNull(Route::getRoutes()->getByRoute('GET', 'api/v1/servers/{id}'));
        $this->assertNotNull(Route::getRoutes()->getByRoute('PUT', 'api/v1/servers/{id}'));
        $this->assertNotNull(Route::getRoutes()->getByRoute('DELETE', 'api/v1/servers/{id}'));
        $this->assertNotNull(Route::getRoutes()->getByRoute('POST', 'api/v1/servers/{id}/scan'));
    }

    public function test_route_parameters_are_validated()
    {
        $response = $this->getJson('/api/v1/users/invalid-id');
        
        $response->assertStatus(404);
    }

    public function test_web_routes_are_registered()
    {
        $webRoutes = Route::getRoutes()->match(request()->create('/'));
        
        $this->assertNotNull($webRoutes);
    }
}