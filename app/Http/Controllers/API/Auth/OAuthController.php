<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Services\OAuthService;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OAuthController extends Controller
{
    use HasApiResponse;

    public function __construct(
        protected OAuthService $oauthService
    ) {
    }

    /**
     * Redirect to OAuth provider.
     */
    public function redirect(
        string $provider
    ): JsonResponse {
        $url = $this->oauthService
            ->redirect($provider);

        return $this->successResponse([
            'redirect_url' => $url,
        ]);
    }

    /**
     * OAuth callback.
     */
    public function callback(
        Request $request,
        string $provider
    ): JsonResponse {

        if ($request->has('error')) {

            return $this->errorResponse(
                'OAuth authentication cancelled.',
                400
            );
        }

        $result = $this->oauthService
            ->callback($provider);

        return $this->successResponse(
            new AuthResource($result),
            'OAuth login successful.'
        );
    }
}
