<?php

use App\Http\Middleware\JwtMiddleware;
use App\Presentation\Http\Controllers\V1\AuthController;
// use App\Http\Controllers\Api\V1\AuthController;
// use App\Http\Controllers\Api\V1\Permission\PermissionController;
use App\Presentation\Http\Controllers\V1\PermissionController;
use App\Presentation\Http\Controllers\V1\RoleController;
use App\Presentation\Http\Controllers\V1\UserController;
use App\Presentation\Http\Controllers\V1\CategoryController;
use App\Presentation\Http\Controllers\V1\LaboratoryController;
use App\Presentation\Http\Controllers\V1\PresentationController;
use App\Presentation\Http\Controllers\V1\PharmaceuticalFormController;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Api\V1\Role\RoleController;
// use App\Http\Controllers\Api\V1\User\UserController;

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
        Route::get('/search', [PermissionController::class, 'search']);
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

    // ------------------------
    // Users
    // ------------------------
    Route::middleware('permission:manager-users')->prefix('v1/user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('{id}', [UserController::class, 'show']);
        Route::put('{id}', [UserController::class, 'update']);
        Route::delete('{id}', [UserController::class, 'destroy']);
    });

    Route::middleware('permission:manager-catalogs')->prefix('v1/category')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('{id}', [CategoryController::class, 'show']);
        Route::put('{id}', [CategoryController::class, 'update']);
        Route::delete('{id}', [CategoryController::class, 'destroy']);
    });

    Route::middleware('permission:manager-catalogs')->prefix('v1/laboratory')->group(function () {
        Route::get('/', [LaboratoryController::class, 'index']);
        Route::post('/', [LaboratoryController::class, 'store']);
        Route::get('{id}', [LaboratoryController::class, 'show']);
        Route::put('{id}', [LaboratoryController::class, 'update']);
        Route::delete('{id}', [LaboratoryController::class, 'destroy']);
    });

    Route::middleware('permission:manager-catalogs')->prefix('v1/presentation')->group(function () {
        Route::get('/', [PresentationController::class, 'index']);
        Route::post('/', [PresentationController::class, 'store']);
        Route::get('{id}', [PresentationController::class, 'show']);
        Route::put('{id}', [PresentationController::class, 'update']);
        Route::delete('{id}', [PresentationController::class, 'destroy']);
    });

    Route::middleware('permission:manager-catalogs')->prefix('v1/pharmaceutical-form')->group(function () {
        Route::get('/', [PharmaceuticalFormController::class, 'index']);
        Route::post('/', [PharmaceuticalFormController::class, 'store']);
        Route::get('{id}', [PharmaceuticalFormController::class, 'show']);
        Route::put('{id}', [PharmaceuticalFormController::class, 'update']);
        Route::delete('{id}', [PharmaceuticalFormController::class, 'destroy']);
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
