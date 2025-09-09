<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JwtMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Permission\PermissionController;

// Rutas públicas
Route::prefix('v1/auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

// --- Rutas protegidas por JWT ---
Route::middleware([JwtMiddleware::class])->prefix('v1')->group(function () {

    // Auth
    Route::get('auth/me', [AuthController::class, 'me']);

    // Permissions con Spatie
    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->middleware('permission:manager-permissions');
    });

});
/*
Route::middleware([JwtMiddleware::class])->group(function () {

    // Auth endpoints protegidos
    Route::prefix('v1/auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

    // Permissions endpoints protegidos y con permisos
    Route::middleware(['permission:manager-permissions'])->prefix('v1/permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::post('/', [PermissionController::class, 'store']);
        Route::get('{id}', [PermissionController::class, 'show']);
        Route::put('{id}', [PermissionController::class, 'update']);
        Route::delete('{id}', [PermissionController::class, 'destroy']);
    });
});*/