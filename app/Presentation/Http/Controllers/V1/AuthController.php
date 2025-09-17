<?php

namespace App\Presentation\Http\Controllers\V1;

use App\Infrastructure\Services\ApiResponseService;
use App\Infrastructure\Services\JwtAuthService;

use App\Http\Controllers\Controller;
use App\Presentation\Http\Requests\V1\LoginRequest;
use App\Presentation\Http\Requests\V1\LogoutRequest;
use App\Presentation\Http\Requests\V1\RefreshTokenRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(protected JwtAuthService $jwtService) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        $token = $this->jwtService->attemptLogin($credentials);

        return response()->json(
            $this->jwtService->respondWithToken($token, 'Login exitoso')
        );
    }

    public function me(): JsonResponse
    {
        $userDTO = \App\Presentation\DTOs\UserDTO::fromModel($this->jwtService->user());

        return response()->json([
            'message' => 'Usuario autenticado',
            'data' => ['user' => $userDTO],
        ]);
    }

    public function logout(): JsonResponse
    {
        \Tymon\JWTAuth\Facades\JWTAuth::invalidate($this->jwtService->user());
        return response()->json(['message' => 'Logout exitoso']);
    }

    public function refresh(): JsonResponse
    {
        $token = \Tymon\JWTAuth\Facades\JWTAuth::refresh();
        return response()->json($this->jwtService->respondWithToken($token, 'Token actualizado'));
    }
}