<?php

namespace App\Services;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\Repositories\Interfaces\UserRepositoryInterface;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * Register new user.
     */
    public function register(
        RegisterDTO $dto
    ): array {
        $user = $this->userRepository->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
            'role' => UserRole::EMPLOYEE,
        ]);

        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Login user.
     */
    public function login(
        LoginDTO $dto
    ): array {
        $user = $this->userRepository
            ->findByEmail($dto->email);

        if (
            !$user ||
            !Hash::check(
                $dto->password,
                $user->password
            )
        ) {
            throw new UnauthorizedHttpException(
                '',
                'Invalid credentials.'
            );
        }

        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Logout authenticated user.
     */
    public function logout(
        User $user
    ): void {
        $user->currentAccessToken()->delete();
    }
}
