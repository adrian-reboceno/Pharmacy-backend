<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Traits\ApiResponseTrait;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    use ApiResponseTrait;

    public function handle($request, Closure $next)
    {
        try {
            // Intenta autenticar el token
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->errorResponse('Usuario no encontrado', [], Response::HTTP_UNAUTHORIZED);
            }
        } catch (TokenExpiredException $e) {
            try {
                // Token expirado: intentar refresh
                $newToken = JWTAuth::refresh(JWTAuth::getToken());
                // Opcional: agregar nuevo token en el header
                $response = $next($request);
                return $response->header('Authorization', 'Bearer ' . $newToken);
            } catch (JWTException $e) {
                return $this->errorResponse('Token expirado y no se pudo refrescar', [], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            return $this->errorResponse('Token inválido', [], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
