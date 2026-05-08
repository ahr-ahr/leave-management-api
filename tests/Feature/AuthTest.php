<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User can register.
     */
    public function test_user_can_register(): void
    {
        $response = $this->postJson(
            '/api/auth/register',
            [
                'name' => 'Haikal',
                'email' => 'haikal@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]
        );

        $response
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Register successful.',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'haikal@gmail.com',
        ]);
    }

    /**
     * User can login.
     */
    public function test_user_can_login(): void
    {
        User::query()->create([
            'name' => 'Haikal',
            'email' => 'haikal@gmail.com',
            'password' => 'password',
        ]);

        $response = $this->postJson(
            '/api/auth/login',
            [
                'email' => 'haikal@gmail.com',
                'password' => 'password',
            ]
        );

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Login successful.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'token',
                ],
            ]);
    }

    /**
     * Authenticated user can logout.
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;

        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token
        )->postJson('/api/auth/logout');

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Logout successful.',
            ]);
    }
}
