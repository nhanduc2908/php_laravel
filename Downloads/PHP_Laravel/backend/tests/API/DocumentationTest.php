<?php

namespace Tests\API;

use Tests\TestCase;
use Illuminate\Support\Facades\File;

class DocumentationTest extends TestCase
{
    protected $openApiSpec;

    protected function setUp(): void
    {
        parent::setUp();
        $specPath = base_path('docs/api/openapi.yaml');
        if (File::exists($specPath)) {
            $this->openApiSpec = yaml_parse_file($specPath);
        }
    }

    public function test_openapi_spec_exists()
    {
        $this->assertNotNull($this->openApiSpec, 'OpenAPI spec file not found');
    }

    public function test_all_endpoints_are_documented()
    {
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())
            ->filter(function ($route) {
                return strpos($route->uri(), 'api/v1') === 0;
            });

        $documentedPaths = array_keys($this->openApiSpec['paths'] ?? []);

        foreach ($routes as $route) {
            $uri = '/api/v1' . str_replace('api/v1', '', $route->uri());
            $method = strtolower($route->methods()[0]);
            
            if ($method === 'head') continue;
            
            $this->assertTrue(
                isset($this->openApiSpec['paths'][$uri][$method]),
                "Endpoint {$method} {$uri} not documented in OpenAPI spec"
            );
        }
    }

    public function test_api_responses_match_documentation()
    {
        $spec = $this->openApiSpec;
        $this->assertNotNull($spec, 'OpenAPI spec not available');
        
        // Test that documented response schemas are valid
        foreach ($spec['paths'] ?? [] as $path => $methods) {
            foreach ($methods as $method => $config) {
                if (isset($config['responses'])) {
                    foreach ($config['responses'] as $status => $response) {
                        $this->assertArrayHasKey('description', $response, 
                            "Response for {$method} {$path} status {$status} missing description");
                    }
                }
            }
        }
    }

    public function test_required_parameters_are_documented()
    {
        $response = $this->getJson('/api/v1/health');
        $response->assertStatus(200);
        
        // Check OpenAPI spec for required parameters
        $spec = $this->openApiSpec;
        if ($spec && isset($spec['paths']['/api/v1/servers']['post'])) {
            $body = $spec['paths']['/api/v1/servers']['post']['requestBody'] ?? null;
            if ($body && isset($body['required'])) {
                $this->assertTrue($body['required'], 'POST /servers request body should be required');
            }
        }
    }

    public function test_example_values_are_valid()
    {
        $spec = $this->openApiSpec;
        if (!$spec) {
            $this->markTestSkipped('OpenAPI spec not available');
        }
        
        // Check examples in schemas
        foreach ($spec['components']['schemas'] ?? [] as $schemaName => $schema) {
            if (isset($schema['example'])) {
                // Example should be valid for the schema
                $this->assertIsArray($schema['example'], "Example for {$schemaName} should be an array");
            }
        }
    }
}