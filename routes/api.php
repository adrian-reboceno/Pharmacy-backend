<?php
# routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

Route::prefix('v1')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::middleware('jwt.verify')->get('me', [AuthController::class, 'me']);
    Route::middleware('jwt.verify')->post('auth/logout', [AuthController::class, 'logout']);
    Route::middleware('jwt.verify')->post('refresh', [AuthController::class, 'refresh']);
});