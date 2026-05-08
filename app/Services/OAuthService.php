<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Laravel\Socialite\Facades\Socialite;

class OAuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Get OAuth redirect URL.
     */
    public function redirect(
        string $provider
    ): string {
        return Socialite::driver($provider)
            ->stateless()
            ->redirect()
            ->getTargetUrl();
    }

    /**
     * Handle OAuth callback.
     *
     * @return array<string, mixed>
     */
    public function callback(
        string $provider
    ): array {
        $socialUser = Socialite::driver($provider)
            ->stateless()
            ->user();

        $user = $this->userRepository
            ->findByProvider(
                $provider,
                $socialUser->getId()
            );

        if (!$user) {
            $user = $this->userRepository
                ->findByEmail(
                    $socialUser->getEmail()
                );

            if ($user) {
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
            } else {
                $user = User::query()->create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'role' => UserRole::EMPLOYEE,
                    'email_verified_at' => now(),
                ]);
            }
        }

        $token = $user->createToken(
            'auth_token'
        )->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
