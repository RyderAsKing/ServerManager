<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApplicationSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_endpoint_is_ok(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_spa_shell_includes_vite_assets(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('id="app"', false);
        $response->assertSee('/build/assets/', false);
        $response->assertSee('fontawesome.css', false);
        $response->assertSee('custom.css', false);
    }

    public function test_login_validation_errors_are_returned(): void
    {
        $this->postJson('/api/user/login', [])
            ->assertOk()
            ->assertJsonPath('error', true)
            ->assertJsonStructure(['validation_errors']);
    }

    public function test_register_login_and_authenticated_api_flow(): void
    {
        $register = $this->postJson('/api/user/register', [
            'name' => 'Smoke Tester',
            'email' => 'smoke@example.com',
            'password' => 'secret123',
        ]);

        $register->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonStructure(['api_token', 'name', 'email']);

        $token = $register->json('api_token');
        $this->assertNotEmpty($token);

        $login = $this->postJson('/api/user/login', [
            'email' => 'smoke@example.com',
            'password' => 'secret123',
        ]);

        $login->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonPath('api_token', $token);

        $this->getJson('/api/user/'.$token)
            ->assertOk()
            ->assertJsonPath('email', 'smoke@example.com');

        $this->withToken($token)
            ->getJson('/api/server')
            ->assertOk();

        $this->withoutToken()
            ->getJson('/api/server')
            ->assertUnauthorized()
            ->assertJsonPath('error', true);
    }

    public function test_token_guard_rejects_invalid_bearer_token(): void
    {
        User::create([
            'name' => 'Existing',
            'email' => 'existing@example.com',
            'password' => Hash::make('secret123'),
            'api_token' => 'valid-token-value',
        ]);

        $this->withToken('invalid-token')
            ->getJson('/api/server')
            ->assertUnauthorized();
    }
}
