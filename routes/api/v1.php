<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JwtMiddleware;
//use App\Http\Controllers\Api\V1\AuthController;
//use App\Http\Controllers\Api\V1\Permission\PermissionController;
use App\Presentation\Http\Controllers\V1\AuthController;
use App\Presentation\Http\Controllers\V1\PermissionController;
use App\Presentation\Http\Controllers\V1\RoleController;
//use App\Http\Controllers\Api\V1\Role\RoleController;
//use App\Http\Controllers\Api\V1\User\UserController;

// ------------------------
// Rutas públicas
// ------------------------
Route::prefix('v1/auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});


// ------------------------
// Rutas protegidas por JWT
// ------------------------
Route::middleware([JwtMiddleware::class])->group(function () {


    // Auth
    Route::prefix('v1/auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

    // ------------------------
    // Permissions (Spatie)
    // ------------------------
    Route::middleware('permission:manager-permissions')->prefix('v1/permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::post('/', [PermissionController::class, 'store']);
        Route::get('{id}', [PermissionController::class, 'show']);
        Route::put('{id}', [PermissionController::class, 'update']);
        Route::delete('{id}', [PermissionController::class, 'destroy']);
    });

    // ------------------------
    // Roles
    // ------------------------
    Route::middleware('permission:manager-permissions')->prefix('v1/roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('{id}', [RoleController::class, 'show']);
        Route::put('{id}', [RoleController::class, 'update']);
        Route::delete('{id}', [RoleController::class, 'destroy']);
    });
   /* Route::middleware('permission:manager-roles')->prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('{id}', [RoleController::class, 'show']);
        Route::put('{id}', [RoleController::class, 'update']);
        Route::delete('{id}', [RoleController::class, 'destroy']);
    });

    // ------------------------
    // Users
    // ------------------------
    Route::middleware('permission:manager-users')->prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('{id}', [UserController::class, 'show']);
        Route::put('{id}', [UserController::class, 'update']);
        Route::delete('{id}', [UserController::class, 'destroy']);
    });*/

});