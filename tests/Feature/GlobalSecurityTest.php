<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create and authenticate a user for all tests
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_global_security_status_endpoint_returns_correct_status(): void
    {
        // Test when not locked
        $response = $this->get('/api/global-security/status');
        $response->assertOk();
        $response->assertJson([
            'is_locked' => false,
            'has_valid_code' => false,
            'is_accessible' => true,
        ]);

        // Test when locked
        session(['global_security_locked' => true]);
        $response = $this->get('/api/global-security/status');
        $response->assertOk();
        $response->assertJson([
            'is_locked' => true,
            'has_valid_code' => false,
            'is_accessible' => false,
        ]);
    }

    public function test_global_security_verify_with_correct_code(): void
    {
        $response = $this->postJson('/api/global-security/verify', [
            'security_code' => '80313',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Security code verified successfully',
        ]);

        // Check session was set correctly
        $this->assertEquals('80313', session('global_security_code'));
        $this->assertNotNull(session('global_security_timestamp'));
        $this->assertFalse(session('global_security_locked'));
    }

    public function test_global_security_verify_with_incorrect_code(): void
    {
        $response = $this->postJson('/api/global-security/verify', [
            'security_code' => '12345',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Invalid security code',
        ]);
    }

    public function test_global_security_toggle_lock(): void
    {
        // Test locking
        $response = $this->postJson('/api/global-security/toggle');
        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'is_locked' => true,
            'message' => 'Security locked',
        ]);

        $this->assertTrue(session('global_security_locked'));

        // Test unlocking
        $response = $this->postJson('/api/global-security/toggle');
        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'is_locked' => false,
            'message' => 'Security unlocked',
        ]);

        $this->assertFalse(session('global_security_locked'));
    }

    public function test_global_security_middleware_redirects_when_locked(): void
    {
        // Set up locked state
        session(['global_security_locked' => true]);

        $response = $this->get('/assets');
        $response->assertRedirect('/global-security');
    }

    public function test_global_security_middleware_allows_access_when_unlocked(): void
    {
        // Set up unlocked state
        session(['global_security_locked' => false]);

        $response = $this->get('/assets');
        $response->assertOk();
    }

    public function test_global_security_middleware_allows_access_with_valid_code(): void
    {
        // Set up locked state but with valid code
        session([
            'global_security_locked' => true,
            'global_security_code' => '80313',
            'global_security_timestamp' => time(),
        ]);

        $response = $this->get('/assets');
        $response->assertOk();
    }

    public function test_global_security_middleware_redirects_with_expired_code(): void
    {
        // Set up locked state with expired code
        session([
            'global_security_locked' => true,
            'global_security_code' => '80313',
            'global_security_timestamp' => time() - 3700, // More than 1 hour ago
        ]);

        $response = $this->get('/assets');
        $response->assertRedirect('/global-security');
    }

    public function test_global_security_show_page_renders(): void
    {
        $response = $this->get('/global-security');
        $response->assertOk();
    }
}
