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
            JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return $this->errorResponse('Token expirado', [], Response::HTTP_UNAUTHORIZED);
        } catch (JWTException $e) {
            return $this->errorResponse('Token inválido', [], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
