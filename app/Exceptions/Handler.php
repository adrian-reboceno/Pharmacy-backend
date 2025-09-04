<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    /**
     * Report or log an exception.
     */
    public function report(Throwable $exception): void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Solo JSON para API
        if ($request->is('api/*')) {

            if ($exception instanceof ModelNotFoundException) {
                return $this->errorResponse(
                    'Recurso no encontrado',
                    [],
                    Response::HTTP_NOT_FOUND
                );
            }

            if ($exception instanceof AuthenticationException) {
                return $this->errorResponse(
                    'No autenticado',
                    [],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            if (method_exists($exception, 'errors')) {
                return $this->errorResponse(
                    'Errores de validación',
                    $exception->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            // Cualquier otro error
            return $this->errorResponse(
                'Error interno',
                ['exception' => $exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        // Para rutas web, usar el render normal
        return parent::render($request, $exception);
    }
}