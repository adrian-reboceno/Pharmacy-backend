<?php
# app/Exceptions/Handler.php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Infrastructure\Services\ApiResponseService;

/**
 * Class Handler
 *
 * Global exception handler for the application.
 *
 * This class manages how exceptions are reported and rendered into
 * HTTP responses, ensuring consistent and structured error handling
 * for API and non-API requests.
 *
 * For API routes (`/api/*`), this handler:
 * - Returns JSON-formatted responses instead of HTML views.
 * - Converts common Laravel and Symfony exceptions into meaningful
 *   and standardized API error responses.
 *
 * Supported exception types:
 * 1️⃣ HttpExceptionInterface — Handles HTTP and permission-related errors.
 * 2️⃣ ValidationException — Handles validation rule failures.
 * 3️⃣ ModelNotFoundException — Handles missing Eloquent models.
 * 4️⃣ AuthenticationException — Handles authentication failures.
 * 5️⃣ Custom PermissionException — Handles domain-specific permission issues.
 * 6️⃣ All other exceptions default to a 500 Internal Server Error (optional fallback).
 *
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * This method can be customized to send exceptions to third-party
     * services such as Sentry, Bugsnag, or custom logging solutions.
     *
     * @param \Throwable $e
     * @return void
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * This method determines how exceptions are transformed into HTTP responses.
     * For API routes, it leverages the ApiResponseService to generate
     * standardized JSON responses.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        $apiResponse = app(ApiResponseService::class);

        if ($request->is('api/*')) {

            // 1️⃣ HTTP or permission-related exceptions
            if ($e instanceof HttpExceptionInterface) {
                return $apiResponse->error(
                    $e->getMessage(),
                    [],
                    $e->getStatusCode()
                );
            }

            // 2️⃣ Validation errors
            if ($e instanceof ValidationException) {
                return $apiResponse->error(
                    'Validation errors',
                    $e->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            // 3️⃣ Model not found
            if ($e instanceof ModelNotFoundException) {
                return $apiResponse->error(
                    'Resource not found',
                    [],
                    Response::HTTP_NOT_FOUND
                );
            }

            // 4️⃣ Authentication errors
            if ($e instanceof AuthenticationException) {
                return $apiResponse->error(
                    'Unauthenticated',
                    [],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            // 5️⃣ Domain-specific permission errors
            if ($e instanceof \App\Domain\Permission\Exceptions\PermissionException) {
                return $apiResponse->error(
                    $e->getMessage(),
                    [],
                    $e->getCode() ?: Response::HTTP_BAD_REQUEST
                );
            }

            // 6️⃣ Fallback for unexpected errors (optional)
            /*
            return $apiResponse->error(
                'Internal server error',
                ['exception' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
            */
        }

        return parent::render($request, $e);
    }
}
