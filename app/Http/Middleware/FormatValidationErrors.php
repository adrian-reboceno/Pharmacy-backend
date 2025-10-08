<?php
# app/Http/Middleware/FormatValidationErrors.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Presentation\Http\Traits\ApiResponseTrait;

/**
 * Class FormatValidationErrors
 *
 * Middleware that intercepts validation exceptions and returns
 * a standardized JSON error response.
 *
 * This middleware ensures that all validation errors are consistently
 * formatted across the API, improving the client’s ability to handle
 * and display error messages.
 *
 * Example response:
 * {
 *     "success": false,
 *     "message": "Validation errors",
 *     "errors": {
 *         "email": ["The email field is required."],
 *         "password": ["The password must be at least 6 characters."]
 *     }
 * }
 *
 * @package App\Http\Middleware
 */
class FormatValidationErrors
{
    use ApiResponseTrait;

    /**
     * Handle an incoming HTTP request.
     *
     * Executes the next middleware or request handler in the pipeline.
     * If a ValidationException is thrown, it catches it and returns
     * a JSON-formatted error response with HTTP status 422 (Unprocessable Entity).
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (ValidationException $e) {
            return $this->errorResponse(
                message: 'Validation errors',
                data: $e->errors(),
                statusCode: 422
            );
        }
    }
}
