<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HasApiResponse
{
    /**
     * Success response.
     */
    protected function successResponse(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Error response.
     */
    protected function errorResponse(
        string $message = 'Error',
        int $status = 400
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
