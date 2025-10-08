<?php
# app/Http/Middleware/JwtMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Presentation\Http\Traits\ApiResponseTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class JwtMiddleware
 *
 * Middleware responsible for handling authentication using JSON Web Tokens (JWT).
 *
 * This middleware validates incoming requests that require authentication
 * by verifying the provided JWT token. It ensures that the user is properly
 * authenticated before granting access to protected routes.
 *
 * Key features:
 * - Validates and authenticates the JWT token.
 * - Handles expired tokens by attempting to refresh them automatically.
 * - Returns standardized JSON error responses when the token is invalid,
 *   expired, or the user cannot be found.
 *
 * @package App\Http\Middleware
 */
class JwtMiddleware
{
    use ApiResponseTrait;

    /**
     * Handle an incoming request.
     *
     * This method attempts to authenticate the user based on the JWT token
     * included in the request. If the token has expired, it attempts to refresh it
     * and includes a new token in the response headers.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        try {
            // Attempt to authenticate the token
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return $this->errorResponse(
                    message: 'User not found',
                    data: [],
                    statusCode: Response::HTTP_UNAUTHORIZED
                );
            }

        } catch (TokenExpiredException $e) {
            try {
                // Token expired: attempt to refresh it
                $newToken = JWTAuth::refresh(JWTAuth::getToken());

                // Optionally include the refreshed token in the response headers
                $response = $next($request);
                return $response->header('Authorization', 'Bearer ' . $newToken);

            } catch (JWTException $e) {
                return $this->errorResponse(
                    message: 'Token expired and could not be refreshed',
                    data: [],
                    statusCode: Response::HTTP_UNAUTHORIZED
                );
            }

        } catch (JWTException $e) {
            return $this->errorResponse(
                message: 'Invalid token',
                data: [],
                statusCode: Response::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }
}
