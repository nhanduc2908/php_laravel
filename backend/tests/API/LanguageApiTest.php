<?php

namespace Tests\API;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\App;

class LanguageApiTest extends TestCase
{
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth()->login($this->user);
    }

    public function test_accept_language_header_works()
    {
        $response = $this->withHeader('Accept-Language', 'vi')
            ->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/dashboard/stats');
        
        $response->assertStatus(200);
        // Response messages should be in Vietnamese
        $data = $response->json();
        $this->assertEquals('vi', App::getLocale());
    }

    public function test_default_language_is_english()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/dashboard/stats');
        
        $response->assertStatus(200);
        $this->assertEquals('en', App::getLocale());
    }

    public function test_invalid_language_falls_back_to_default()
    {
        $response = $this->withHeader('Accept-Language', 'invalid')
            ->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/dashboard/stats');
        
        $response->assertStatus(200);
        $this->assertEquals('en', App::getLocale());
    }

    public function test_error_messages_are_localized()
    {
        // English error
        $responseEn = $this->postJson('/api/v1/auth/login', [
            'email' => 'wrong@test.com',
            'password' => 'wrong'
        ]);
        
        // Vietnamese error
        $responseVi = $this->withHeader('Accept-Language', 'vi')
            ->postJson('/api/v1/auth/login', [
                'email' => 'wrong@test.com',
                'password' => 'wrong'
            ]);
        
        $messageEn = $responseEn->json('message');
        $messageVi = $responseVi->json('message');
        
        $this->assertNotEquals($messageEn, $messageVi);
    }

    public function test_success_messages_are_localized()
    {
        $responseEn = $this->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);
        
        $responseVi = $this->withHeader('Accept-Language', 'vi')
            ->postJson('/api/v1/auth/login', [
                'email' => $this->user->email,
                'password' => 'password'
            ]);
        
        $messageEn = $responseEn->json('message');
        $messageVi = $responseVi->json('message');
        
        $this->assertNotEquals($messageEn, $messageVi);
    }

    public function test_validation_messages_are_localized()
    {
        $responseEn = $this->postJson('/api/v1/auth/register', []);
        $responseVi = $this->withHeader('Accept-Language', 'vi')
            ->postJson('/api/v1/auth/register', []);
        
        $errorsEn = $responseEn->json('errors');
        $errorsVi = $responseVi->json('errors');
        
        $this->assertNotEquals($errorsEn['name'][0] ?? '', $errorsVi['name'][0] ?? '');
    }

    public function test_language_persists_after_login()
    {
        // Login with Vietnamese header
        $response = $this->withHeader('Accept-Language', 'vi')
            ->postJson('/api/v1/auth/login', [
                'email' => $this->user->email,
                'password' => 'password'
            ]);
        
        $token = $response->json('data.access_token');
        
        // Subsequent request should maintain Vietnamese
        $response2 = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/auth/me');
        
        $response2->assertStatus(200);
        $this->assertEquals('vi', App::getLocale());
    }

    public function test_supported_languages_endpoint()
    {
        $response = $this->getJson('/api/v1/languages');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['code', 'name', 'native_name', 'flag']
                ]
            ]);
        
        $languages = $response->json('data');
        $codes = array_column($languages, 'code');
        
        $this->assertContains('en', $codes);
        $this->assertContains('vi', $codes);
        $this->assertContains('ja', $codes);
    }
}