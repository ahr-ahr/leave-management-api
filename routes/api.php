<?php

use App\Http\Controllers\API\Auth\{AuthController, OAuthController};
use App\Http\Controllers\API\Employee\LeaveController;
use App\Http\Controllers\API\Admin\LeaveApprovalController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'json',
])->group(function () {
    Route::prefix('auth')->group(function () {

        Route::post('/register', [
            AuthController::class,
            'register',
        ]);

        Route::post('/login', [
            AuthController::class,
            'login',
        ]);

        Route::get(
            '/{provider}/redirect',
            [OAuthController::class, 'redirect']
        );

        Route::get(
            '/{provider}/callback',
            [OAuthController::class, 'callback']
        );

        Route::middleware('auth:sanctum')->group(function () {

            Route::post('/logout', [
                AuthController::class,
                'logout',
            ]);

        });

    });

    // Route::middleware([
//     'auth:sanctum',
//     'role:admin',
// ])->get('/admin-test', function () {

    //     return response()->json([
//         'message' => 'Welcome admin',
//     ]);

    // });

    Route::middleware([
        'auth:sanctum',
        'role:employee',
    ])->prefix('employee')->group(function () {

        Route::get(
            '/leaves',
            [LeaveController::class, 'index']
        );

        Route::post(
            '/leaves',
            [LeaveController::class, 'store']
        );

    });

    Route::middleware([
        'auth:sanctum',
        'role:admin',
    ])->prefix('admin')->group(function () {

        Route::get(
            '/leaves/pending',
            [LeaveApprovalController::class, 'pending']
        );

        Route::patch(
            '/leaves/{id}/approve',
            [LeaveApprovalController::class, 'approve']
        );

        Route::patch(
            '/leaves/{id}/reject',
            [LeaveApprovalController::class, 'reject']
        );

    });

});
