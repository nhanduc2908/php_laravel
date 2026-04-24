<?php

namespace Tests\Security;

use Tests\TestCase;
use App\Models\User;

class SqlInjectionTest extends TestCase
{
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth()->login($this->user);
    }

    public function test_login_endpoint_resists_sql_injection()
    {
        $maliciousInputs = [
            "' OR '1'='1",
            "admin' --",
            "1' OR '1' = '1'",
            "' UNION SELECT NULL--",
            "'; DROP TABLE users; --",
            "admin' OR 1=1--",
            "' OR 'x'='x",
            "anything' OR 'x'='x",
            "' OR 1=1 LIMIT 1--"
        ];

        foreach ($maliciousInputs as $input) {
            $response = $this->postJson('/api/v1/auth/login', [
                'email' => $input,
                'password' => 'anything'
            ]);
            
            // Should not authenticate and should not cause SQL error
            $response->assertStatus(401);
            $response->assertJson(['status' => 'error']);
        }
    }

    public function test_user_search_resists_sql_injection()
    {
        $maliciousQueries = [
            "' OR 1=1--",
            "'; DELETE FROM users; --",
            "1' UNION SELECT email,password FROM users--",
            "admin' AND 1=0 UNION ALL SELECT 1,2,3--"
        ];

        foreach ($maliciousQueries as $query) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                ->getJson('/api/v1/users?search=' . urlencode($query));
            
            // Should return 200 (no SQL error) and not expose data
            $response->assertStatus(200);
            
            $data = $response->json('data.data', []);
            foreach ($data as $user) {
                $this->assertNotEquals('admin', $user['email']);
            }
        }
    }

    public function test_criteria_filter_resists_sql_injection()
    {
        $maliciousFilters = [
            "1' OR '1'='1",
            "' UNION SELECT * FROM users--",
            "'; DROP TABLE criteria; --"
        ];

        foreach ($maliciousFilters as $filter) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                ->getJson('/api/v1/criteria?search=' . urlencode($filter));
            
            $response->assertStatus(200);
            $response->assertJsonStructure(['status', 'data']);
        }
    }

    public function test_order_by_resists_sql_injection()
    {
        $maliciousOrderBy = [
            "id; DROP TABLE users--",
            "(SELECT COUNT(*) FROM users)",
            "CASE WHEN 1=1 THEN id ELSE name END"
        ];

        foreach ($maliciousOrderBy as $order) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                ->getJson('/api/v1/servers?sort=' . urlencode($order));
            
            $response->assertStatus(200);
        }
    }

    public function test_numeric_parameters_are_sanitized()
    {
        $maliciousIds = [
            "1 OR 1=1",
            "1; DROP TABLE servers",
            "1 UNION SELECT 1",
            "' OR '1'='1"
        ];

        foreach ($maliciousIds as $id) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                ->getJson('/api/v1/users/' . urlencode($id));
            
            // Should return 404 (not found) or 400 (bad request), not SQL error
            $response->assertStatus(404);
        }
    }
}