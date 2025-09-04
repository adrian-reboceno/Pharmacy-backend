<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RefreshTokenRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponseTrait;
use App\Traits\JwtAuthHelpers;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponseTrait, JwtAuthHelpers;

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return $this->errorResponse(
                'Credenciales incorrectas',
                [],
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $this->respondWithToken($token, 'Inicio de sesión exitoso');
    }

    public function me()
    {
        return $this->successResponse(Auth::user()->toArray(), 'Usuario autenticado');
    }

    public function logout(LogoutRequest $request)
    {
        Auth::logout();

        return $this->successResponse([], 'Sesión cerrada correctamente');
    }

    public function refresh(RefreshTokenRequest $request)
    {
        return $this->respondWithToken(Auth::refresh(), 'Token renovado');
    }
}