<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle incoming request.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if (
            !$request->expectsJson()
            && !$request->is('api/auth/*/callback')
            && !$request->is('api/auth/*/redirect')
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Accept header must be application/json.',
            ], 406);
        }

        return $next($request);
    }
}
