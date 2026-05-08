<?php

namespace App\Http\Controllers\API\Auth;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\{UserResource, AuthResource};
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\HasApiResponse;

class AuthController extends Controller
{
    use HasApiResponse;

    public function __construct(
        protected AuthService $authService
    ) {
    }

    /**
     * Register new user.
     */
    public function register(
        RegisterRequest $request
    ): JsonResponse {
        $result = $this->authService->register(
            RegisterDTO::fromRequest($request)
        );

        return $this->successResponse(
            new AuthResource($result),
            'Register successful.',
            201
        );
    }

    /**
     * Login user.
     */
    public function login(
        LoginRequest $request
    ): JsonResponse {
        $result = $this->authService->login(
            LoginDTO::fromRequest($request)
        );

        return $this->successResponse(
            new AuthResource($result),
            'Login successful.'
        );
    }

    /**
     * Logout authenticated user.
     */
    public function logout(
        Request $request
    ): JsonResponse {
        $this->authService->logout(
            $request->user()
        );

        return $this->successResponse(
            null,
            'Logout successful.'
        );
    }
}
